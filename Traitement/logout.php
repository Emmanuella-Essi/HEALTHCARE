<?php
// ============================================================
// logout.php — Déconnexion sécurisée du médecin
// Détruit la session et redirige vers login
// ============================================================

require_once 'config.php';
startSession();

// ---- Supprime toutes les variables de session ----
$_SESSION = [];

// ---- Supprime le cookie de session s'il existe ----
if (isset($_COOKIE[session_name()])) {
    setcookie(
        session_name(), // Nom du cookie de session
        '',             // Valeur vide
        time() - 3600,  // Date dans le passé = suppression immédiate
        '/',
        '',
        false,
        true
    );
}

// ---- Détruit complètement la session côté serveur ----
session_destroy();

// ---- Redirige vers la page de connexion ----
header('Location: login.html');
exit;