<?php
// ============================================================
// config.php — Configuration globale partagée par tous les PHP
// Connexion BDD + fonctions utilitaires communes
// ============================================================

// ---- Paramètres de connexion MySQL ----
define('DB_HOST',    'localhost');    // Hôte MySQL
define('DB_NAME',    'medidash_db'); // Nom de la base de données
define('DB_USER',    'root');        // Utilisateur MySQL
define('DB_PASS',    '');            // Mot de passe (vide sur XAMPP)
define('DB_CHARSET', 'utf8mb4');     // Charset complet

// ---- Connexion PDO unique partagée ----
function getDb(): PDO {
    static $pdo = null;              // Instance unique (singleton)
    if ($pdo !== null) return $pdo;  // Retourne la connexion existante

    $dsn  = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Exceptions sur erreur
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Tableaux associatifs
        PDO::ATTR_EMULATE_PREPARES   => false,                   // Requêtes préparées natives
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
        return $pdo;
    } catch (PDOException $e) {
        // Redirige vers une page d'erreur si la BDD est inaccessible
        die(json_encode(['success'=>false,'error'=>'Connexion BDD impossible.']));
    }
}

// ---- Démarrage sécurisé de la session ----
function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 86400,    // Session valide 24h
            'httponly' => true,     // Cookie non accessible en JS (sécurité XSS)
            'samesite' => 'Strict', // Protection CSRF
        ]);
        session_start();
    }
}

// ---- Vérification authentification ----
// Retourne l'ID du médecin ou redirige vers login
function requireAuth(bool $jsonResponse = false): int {
    startSession();
    if (empty($_SESSION['medecin_id'])) {
        if ($jsonResponse) {
            http_response_code(401);
            echo json_encode(['success'=>false,'error'=>'Non autorisé. Connectez-vous.']);
            exit;
        }
        header('Location: login.php');
        exit;
    }
    return (int) $_SESSION['medecin_id'];
}

// ---- Réponses JSON standardisées ----
function jsonSuccess(array $data = [], string $msg = ''): void {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success' => true,
        'message' => $msg,
        'data'    => $data,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError(int $code, string $msg): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['success'=>false,'error'=>$msg], JSON_UNESCAPED_UNICODE);
    exit;
}

// ---- Redirection avec message flash (pour formulaires HTML) ----
function redirect(string $url, string $msg = '', string $type = 'success'): void {
    startSession();
    if ($msg) {
        // Compatibilité avec les pages UI (ex: Accueil/inscription.php)
        $_SESSION['flash_msg'] = $msg;
        $_SESSION['flash_type'] = $type; // 'success' | 'error' | 'info'

        // Alias attendu par Accueil/inscription.php et Accueil/index.php
        $_SESSION['flash_message'] = $msg;
        $_SESSION['flashType'] = $type;
        $_SESSION['flash_type_ui'] = $type;
    }
    header("Location: $url");
    exit;
}


// ---- Récupérer et effacer le message flash ----
function getFlash(): array {
    startSession();
    $msg  = $_SESSION['flash_msg']  ?? '';
    $type = $_SESSION['flash_type'] ?? 'info';
    unset($_SESSION['flash_msg'], $_SESSION['flash_type']); // Efface après lecture
    return ['msg' => $msg, 'type' => $type];
}

// ---- Nettoyage des entrées utilisateur ----
function clean(string $val): string {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

// ---- Validation d'un email ----
function isEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// ---- Validation d'une date YYYY-MM-DD ----
function isDate(string $date): bool {
    return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
}

// ---- Hash sécurisé du mot de passe ----
function hashPwd(string $pwd): string {
    return password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);
}

// ---- Vérification du mot de passe ----
function verifyPwd(string $pwd, string $hash): bool {
    return password_verify($pwd, $hash);
}