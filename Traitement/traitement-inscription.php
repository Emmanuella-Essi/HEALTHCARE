<?php
// ============================================================
// traitement_inscription.php — Création d'un compte médecin
// Valide le formulaire d'inscription et insère en BDD
// ============================================================

require_once 'config.php';

startSession();

// ---- Redirige si déjà connecté ----
if (!empty($_SESSION['medecin_id'])) {
    header('Location: index.html');
    exit;
}

// ---- Vérifie méthode POST ----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('register.html', 'Accès non autorisé.', 'error');
}

// ---- Lecture des champs du formulaire d'inscription ----
$prenom      = clean($_POST['prenom']      ?? '');
$nom         = clean($_POST['nom']         ?? '');
$email       = clean($_POST['email']       ?? '');
$telephone   = clean($_POST['telephone']   ?? '');
$specialite  = clean($_POST['specialite']  ?? '');
$password    = trim($_POST['password']     ?? '');
$confirm     = trim($_POST['confirm_pwd']  ?? '');

// ---- Validation des champs obligatoires ----
$errors = [];

if (empty($prenom))     $errors[] = "Le prénom est obligatoire.";
if (empty($nom))        $errors[] = "Le nom est obligatoire.";
if (empty($email))      $errors[] = "L'email est obligatoire.";
if (!isEmail($email))   $errors[] = "Format d'email invalide.";
if (empty($password))   $errors[] = "Le mot de passe est obligatoire.";

// Règles de sécurité du mot de passe
if (strlen($password) < 8) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
}
if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
}
if (!preg_match('/[0-9]/', $password)) {
    $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
}
if ($password !== $confirm) {
    $errors[] = "Les mots de passe ne correspondent pas.";
}

// Si erreurs de validation, retourne à l'inscription
if (!empty($errors)) {
    redirect('register.html', implode(' | ', $errors), 'error');
}

// ---- Gestion de l'upload de photo de profil ----
$photoPath = ''; // Chemin de la photo (vide par défaut)

if (!empty($_FILES['photo']['name'])) {
    $file      = $_FILES['photo'];
    $maxSize   = 2 * 1024 * 1024; // 2 Mo maximum
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp']; // Types autorisés

    // Vérifie la taille
    if ($file['size'] > $maxSize) {
        redirect('register.html', 'Photo trop lourde (max 2 Mo).', 'error');
    }

    // Vérifie le type MIME réel (pas seulement l'extension)
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']); // Type MIME réel du fichier

    if (!in_array($mimeType, $allowedTypes)) {
        redirect('register.html', 'Format photo invalide (JPG, PNG ou WebP uniquement).', 'error');
    }

    // Génère un nom unique pour éviter les collisions
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION); // Extension du fichier
    $filename = 'medecin_' . uniqid() . '.' . $ext;          // Nom unique
    $destDir  = 'uploads/photos/';                           // Dossier de destination

    // Crée le dossier si inexistant
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true); // Crée récursivement avec permissions 755
    }

    $dest = $destDir . $filename; // Chemin complet

    // Déplace le fichier temporaire vers le dossier final
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $photoPath = $dest; // Mémorise le chemin pour la BDD
    }
}

// ---- Connexion BDD ----
$pdo = getDb();

// ---- Vérifie que l'email n'est pas déjà utilisé ----
$check = $pdo->prepare("SELECT id FROM medecins WHERE email = :email LIMIT 1");
$check->execute([':email' => $email]);
if ($check->fetch()) {
    // Email déjà enregistré — ne précise pas si c'est un compte actif ou non (sécurité)
    redirect('register.html', 'Cet email est déjà utilisé.', 'error');
}

// ---- Insertion du médecin en BDD ----
$sql = "
    INSERT INTO medecins
        (prenom, nom, email, telephone, specialite, mot_de_passe, photo, actif, created_at)
    VALUES
        (:prenom, :nom, :email, :tel, :spec, :pwd, :photo, 1, NOW())
";
// actif = 1 : compte immédiatement actif (en prod, mettre 0 pour validation admin)

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':prenom' => $prenom,
    ':nom'    => $nom,
    ':email'  => $email,
    ':tel'    => $telephone ?: null,    // null si non fourni
    ':spec'   => $specialite ?: null,
    ':pwd'    => hashPwd($password),    // Hash bcrypt sécurisé
    ':photo'  => $photoPath ?: null,
]);

$newId = (int) $pdo->lastInsertId(); // ID du nouveau médecin

// ---- Connecte automatiquement après inscription ----
session_regenerate_id(true);
$_SESSION['medecin_id']    = $newId;
$_SESSION['medecin_nom']   = $prenom . ' ' . $nom;
$_SESSION['medecin_email'] = $email;
$_SESSION['medecin_photo'] = $photoPath;
$_SESSION['login_time']    = time();

// ---- Redirige vers le dashboard ----
redirect('index.html', 'Compte créé avec succès ! Bienvenue Dr. ' . $prenom . '.', 'success');