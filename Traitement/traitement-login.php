<?php
// ============================================================
// traitement_login.php — Traitement du formulaire de connexion
// Vérifie email + mot de passe → crée la session médecin
// ============================================================

require_once 'config.php'; // Charge la config et les fonctions

// Démarre la session PHP sécurisée
startSession();

// ---- Redirige si déjà connecté ----
if (!empty($_SESSION['medecin_id'])) {
    header('Location: index.html'); // Déjà connecté → dashboard
    exit;
}

// ---- Vérifie que la méthode est POST ----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.html', 'Accès non autorisé.', 'error');
}

// ---- Lecture et nettoyage des champs du formulaire ----
$email    = clean($_POST['email']    ?? ''); // Email saisi
$password = trim($_POST['password']  ?? ''); // Mot de passe (ne pas altérer avec clean)
$remember = isset($_POST['remember']);        // Case "Se souvenir de moi"

// ---- Validation des champs ----
if (empty($email)) {
    redirect('login.html', 'L\'email est obligatoire.', 'error');
}

if (!isEmail($email)) {
    redirect('login.html', 'Format d\'email invalide.', 'error');
}

if (empty($password)) {
    redirect('login.html', 'Le mot de passe est obligatoire.', 'error');
}

if (strlen($password) < 6) {
    redirect('login.html', 'Mot de passe trop court (minimum 6 caractères).', 'error');
}

// ---- Recherche du médecin en base de données ----
$pdo  = getDb();
$stmt = $pdo->prepare("
    SELECT id, prenom, nom, email, mot_de_passe, specialite, photo
    FROM medecins
    WHERE email = :email
    AND   actif = 1        -- Seulement les comptes actifs
    LIMIT 1
");
$stmt->execute([':email' => $email]);
$medecin = $stmt->fetch(); // Retourne le médecin ou false

// ---- Vérification du mot de passe ----
// Ne pas distinguer "email introuvable" et "mauvais mot de passe" (sécurité)
if (!$medecin || !verifyPwd($password, $medecin['mot_de_passe'])) {
    // Incrémente le compteur d'échecs de connexion (protection brute force)
    if ($medecin) {
        $pdo->prepare("
            UPDATE medecins
            SET tentatives_connexion = tentatives_connexion + 1,
                derniere_tentative   = NOW()
            WHERE id = :id
        ")->execute([':id' => $medecin['id']]);
    }
    redirect('login.html', 'Email ou mot de passe incorrect.', 'error');
}

// ---- Vérifie que le compte n'est pas bloqué (trop de tentatives) ----
if (($medecin['tentatives_connexion'] ?? 0) >= 5) {
    redirect('login.html', 'Compte temporairement bloqué. Contactez l\'administrateur.', 'error');
}

// ---- Connexion réussie : crée la session ----
session_regenerate_id(true); // Régénère l'ID session (protection session fixation)

$_SESSION['medecin_id']     = $medecin['id'];        // ID en session
$_SESSION['medecin_nom']    = $medecin['prenom'] . ' ' . $medecin['nom']; // Nom complet
$_SESSION['medecin_email']  = $medecin['email'];
$_SESSION['medecin_photo']  = $medecin['photo'] ?? ''; // Photo de profil (peut être vide)
$_SESSION['login_time']     = time();                  // Timestamp de connexion

// ---- Option "Se souvenir de moi" : prolonge la session ----
if ($remember) {
    // Cookie de session valide 30 jours
    setcookie(session_name(), session_id(), time() + (30 * 24 * 3600), '/', '', false, true);
}

// ---- Réinitialise le compteur de tentatives ----
$pdo->prepare("
    UPDATE medecins
    SET tentatives_connexion = 0,
        derniere_connexion   = NOW() -- Enregistre la date de dernière connexion
    WHERE id = :id
")->execute([':id' => $medecin['id']]);

// ---- Redirige vers le dashboard ----
redirect('index.html', 'Bienvenue Dr. ' . $medecin['prenom'] . ' !', 'success');