<?php
// ============================================================
// traitement_profil.php — Modification du profil médecin
// Gère : infos personnelles, changement mot de passe, photo
// ============================================================

require_once 'config.php';

startSession();
$mid = requireAuth(false); // Redirige vers login si non connecté
$pdo = getDb();

$action = clean($_POST['action'] ?? '');

switch ($action) {
    case 'modifier_infos':    modifierInfos($pdo, $mid);    break;
    case 'changer_password':  changerPassword($pdo, $mid);  break;
    case 'changer_photo':     changerPhoto($pdo, $mid);     break;
    default: redirect('profil.html','Action inconnue.','error');
}

// ============================================================
// MODIFIER LES INFORMATIONS PERSONNELLES
// form: prenom, nom, telephone, specialite, email
// ============================================================
function modifierInfos(PDO $pdo, int $mid): void {
    $prenom    = clean($_POST['prenom']    ?? '');
    $nom       = clean($_POST['nom']       ?? '');
    $email     = clean($_POST['email']     ?? '');
    $tel       = clean($_POST['telephone'] ?? '');
    $spec      = clean($_POST['specialite']?? '');

    // Validations
    if (empty($prenom)) redirect('profil.html','Prénom obligatoire.','error');
    if (empty($nom))    redirect('profil.html','Nom obligatoire.','error');
    if (empty($email) || !isEmail($email)) redirect('profil.html','Email invalide.','error');

    // Vérifie que l'email n'est pas utilisé par un autre médecin
    $chk = $pdo->prepare("SELECT id FROM medecins WHERE email=:e AND id!=:mid LIMIT 1");
    $chk->execute([':e'=>$email,':mid'=>$mid]);
    if ($chk->fetch()) redirect('profil.html','Cet email est déjà utilisé.','error');

    // Mise à jour en BDD
    $pdo->prepare("
        UPDATE medecins
        SET prenom=:prenom, nom=:nom, email=:email, telephone=:tel, specialite=:spec, updated_at=NOW()
        WHERE id=:mid
    ")->execute([':prenom'=>$prenom,':nom'=>$nom,':email'=>$email,':tel'=>$tel?:null,':spec'=>$spec?:null,':mid'=>$mid]);

    // Mise à jour de la session avec les nouvelles infos
    $_SESSION['medecin_nom']   = "$prenom $nom";
    $_SESSION['medecin_email'] = $email;

    redirect('profil.html','Profil mis à jour avec succès.','success');
}

// ============================================================
// CHANGER LE MOT DE PASSE
// form: password_actuel, nouveau_password, confirm_password
// ============================================================
function changerPassword(PDO $pdo, int $mid): void {
    $actuel   = trim($_POST['password_actuel']  ?? '');
    $nouveau  = trim($_POST['nouveau_password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    if (empty($actuel))  redirect('profil.html','Mot de passe actuel obligatoire.','error');
    if (empty($nouveau)) redirect('profil.html','Nouveau mot de passe obligatoire.','error');

    // Règles de sécurité du nouveau mot de passe
    if (strlen($nouveau) < 8)                redirect('profil.html','Minimum 8 caractères.','error');
    if (!preg_match('/[A-Z]/', $nouveau))     redirect('profil.html','Au moins une majuscule.','error');
    if (!preg_match('/[0-9]/', $nouveau))     redirect('profil.html','Au moins un chiffre.','error');
    if ($nouveau !== $confirm)               redirect('profil.html','Les mots de passe ne correspondent pas.','error');
    if ($nouveau === $actuel)                redirect('profil.html','Le nouveau mot de passe doit être différent.','error');

    // Récupère le hash actuel depuis la BDD
    $stmt = $pdo->prepare("SELECT mot_de_passe FROM medecins WHERE id=:mid LIMIT 1");
    $stmt->execute([':mid'=>$mid]);
    $medecin = $stmt->fetch();

    // Vérifie que l'ancien mot de passe est correct
    if (!$medecin || !verifyPwd($actuel, $medecin['mot_de_passe'])) {
        redirect('profil.html','Mot de passe actuel incorrect.','error');
    }

    // Met à jour avec le nouveau hash bcrypt
    $pdo->prepare("UPDATE medecins SET mot_de_passe=:pwd, updated_at=NOW() WHERE id=:mid")
        ->execute([':pwd'=>hashPwd($nouveau),':mid'=>$mid]);

    // Invalide la session et force une re-connexion (sécurité)
    session_destroy();
    redirect('login.html','Mot de passe modifié. Reconnectez-vous.','success');
}

// ============================================================
// CHANGER LA PHOTO DE PROFIL
// form: photo (file input)
// ============================================================
function changerPhoto(PDO $pdo, int $mid): void {
    if (empty($_FILES['photo']['name'])) {
        redirect('profil.html','Aucune photo sélectionnée.','error');
    }

    $file     = $_FILES['photo'];
    $maxSize  = 3 * 1024 * 1024; // 3 Mo maximum
    $types    = ['image/jpeg','image/png','image/webp']; // Types autorisés

    // Vérifie la taille du fichier
    if ($file['size'] > $maxSize) {
        redirect('profil.html','Photo trop lourde (max 3 Mo).','error');
    }

    // Vérifie le type MIME réel (protection upload malveillant)
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    if (!in_array($mimeType, $types)) {
        redirect('profil.html','Format invalide. Utilisez JPG, PNG ou WebP.','error');
    }

    // Supprime l'ancienne photo si elle existe
    $oldStmt = $pdo->prepare("SELECT photo FROM medecins WHERE id=:mid LIMIT 1");
    $oldStmt->execute([':mid'=>$mid]);
    $old = $oldStmt->fetchColumn();
    if ($old && file_exists($old)) {
        unlink($old); // Supprime l'ancien fichier du serveur
    }

    // Génère un nom de fichier unique et sécurisé
    $ext      = match($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => 'jpg',
    };
    $filename = 'medecin_' . $mid . '_' . uniqid() . '.' . $ext;
    $dir      = 'uploads/photos/';

    // Crée le dossier si nécessaire
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $dest = $dir . $filename;

    // Déplace et sauvegarde
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        redirect('profil.html','Erreur lors de l\'upload. Réessayez.','error');
    }

    // Met à jour le chemin de la photo en BDD
    $pdo->prepare("UPDATE medecins SET photo=:photo, updated_at=NOW() WHERE id=:mid")
        ->execute([':photo'=>$dest,':mid'=>$mid]);

    // Met à jour la session
    $_SESSION['medecin_photo'] = $dest;

    redirect('profil.html','Photo de profil mise à jour.','success');
}