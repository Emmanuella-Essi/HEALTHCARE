<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
    header('Location: ../Accueil/index.php?role=admin');
    exit;
}

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../Accueil/index.php?role=admin');
    exit;
}

$adminPrenom = $_SESSION['prenom'] ?? 'Admin';
$adminNom = $_SESSION['nom'] ?? 'Systeme';
$adminInitiales = strtoupper(substr($adminPrenom, 0, 1) . substr($adminNom, 0, 1));
if (trim($adminInitiales) === '') {
    $adminInitiales = 'AD';
}
