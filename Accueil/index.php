<?php
if (php_sapi_name() !== 'cli') {
    $pathInfo = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    if (stripos($pathInfo, '/api/') !== false) {
        require_once __DIR__ . '/../BD/config/database.php';
        require_once __DIR__ . '/../BD/middleware/auth.php';
        require_once __DIR__ . '/../BD/utils/Response.php';
        require_once __DIR__ . '/../BD/utils/Validator.php';
        require_once __DIR__ . '/../BD/Controllers/AuthControllers.php';
        require_once __DIR__ . '/../BD/Controllers/VaccinationControllers.php';
        require_once __DIR__ . '/../BD/Controllers/DocumentController.php';
        require_once __DIR__ . '/../BD/Controllers/MessageControllers.php';

        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim(preg_replace('#/+#', '/', $uri), '/');
        $segments = array_values(array_filter(explode('/', $uri)));
        $apiIndex = array_search('api', $segments, true);

        if ($apiIndex === false) {
            http_response_code(404);
            echo json_encode(['erreur' => 'Route introuvable']);
            exit;
        }

        $segments = array_slice($segments, $apiIndex + 1);
        $s0 = $segments[0] ?? '';
        $s1 = (int)($segments[1] ?? 0);
        $s2 = $segments[2] ?? '';

        try {
            if ($s0 === 'auth') {
                match([$method, $segments[1] ?? '']) {
                    ['POST', 'register'] => AuthController::register(),
                    ['POST', 'login'] => AuthController::login(),
                    ['POST', 'logout'] => AuthController::logout(),
                    ['GET', 'me'] => AuthController::me(),
                    default => Response::error('Route inconnue', 404),
                };
            } elseif ($s0 === 'vaccins' && $method === 'GET') {
                VaccinationController::listVaccins();
            } elseif ($s0 === 'patients' && $s2 === 'vaccinations') {
                match($method) {
                    'GET' => VaccinationController::carnetPatient($s1),
                    'POST' => VaccinationController::ajouter($s1),
                    default => Response::error('Methode non autorisee', 405),
                };
            } elseif ($s0 === 'patients' && $s2 === 'rappels') {
                VaccinationController::rappels($s1);
            } elseif ($s0 === 'patients' && $s2 === 'documents') {
                match($method) {
                    'GET' => DocumentController::liste($s1),
                    'POST' => DocumentController::upload($s1),
                    default => Response::error('Methode non autorisee', 405),
                };
            } elseif ($s0 === 'vaccinations' && $s1 && $method === 'DELETE') {
                VaccinationController::supprimer($s1);
            } elseif ($s0 === 'vaccinations' && ($segments[1] ?? '') === 'stats') {
                VaccinationController::stats();
            } elseif ($s0 === 'consultations') {
                Response::error('Consultations non couvert dans ce fichier', 501);
            } elseif ($s0 === 'medecins') {
                Response::error('Medecins non couvert dans ce fichier', 501);
            } elseif ($s0 === 'documents' && $s1 && $method === 'DELETE') {
                DocumentController::supprimer($s1);
            } else {
                Response::error('Route introuvable', 404);
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::error('Erreur base de donnees', 500);
        } catch (Exception $e) {
            error_log($e->getMessage());
            Response::error('Erreur serveur', 500);
        }
        exit;
    }
}

session_start();

function redirectByRole(string $role): string {
    return match ($role) {
        'medecin' => '../Medecin/accueil.php',
        'admin' => '../Admin/accueil.php',
        default => '../Patient/accueil.php',
    };
}

function setFlash(string $message, string $type = 'error'): void {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'login') {
    require_once __DIR__ . '/../BD/config/database.php';

    $role = $_POST['role'] ?? 'patient';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['mot_de_passe'] ?? '';
    $numeroOrdre = trim($_POST['numero_ordre'] ?? '');

    if (!in_array($role, ['patient', 'medecin', 'admin'], true) || $email === '' || $password === '') {
        setFlash('Veuillez remplir tous les champs obligatoires.');
        header('Location: index.php?role=' . urlencode($role));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('Adresse email invalide.');
        header('Location: index.php?role=' . urlencode($role));
        exit;
    }

    try {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT id, nom, prenom, email, mot_de_passe, role
            FROM utilisateurs
            WHERE email = :email AND role = :role AND est_actif = 1
            LIMIT 1
        ");
        $stmt->execute([
            ':email' => $email,
            ':role' => $role,
        ]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            setFlash('Email ou mot de passe incorrect.');
            header('Location: index.php?role=' . urlencode($role));
            exit;
        }

        if ($role === 'medecin') {
            if ($numeroOrdre === '') {
                setFlash("Veuillez saisir le numero d'ordre medical.");
                header('Location: index.php?role=medecin');
                exit;
            }

            $stmt = $pdo->prepare("SELECT id FROM medecins WHERE user_id = :user_id AND numero_ordre = :numero_ordre LIMIT 1");
            $stmt->execute([
                ':user_id' => $user['id'],
                ':numero_ordre' => $numeroOrdre,
            ]);

            if (!$stmt->fetch()) {
                setFlash("Numero d'ordre medical incorrect.");
                header('Location: index.php?role=medecin');
                exit;
            }
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['derniere_activite'] = time();

        setFlash('Connexion reussie.', 'success');
        header('Location: ' . redirectByRole($user['role']));
        exit;
    } catch (Throwable $e) {
        error_log($e->getMessage());
        setFlash('Connexion impossible. Verifiez que la base healthcare est importee et que MySQL est lance.');
        header('Location: index.php?role=' . urlencode($role));
        exit;
    }
}

$activeRole = $_GET['role'] ?? 'patient';
if (!in_array($activeRole, ['patient', 'medecin', 'admin'], true)) {
    $activeRole = 'patient';
}

$flashMessage = $_SESSION['flash_message'] ?? '';
$flashType = $_SESSION['flash_type'] ?? 'error';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare - Connexion</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .tab-form { display: none; }
        .tab-form.active { display: block; }
        .login-tab.active {
            background-color: #1a3a5c;
            color: white;
            border-radius: 6px;
        }
        .flash {
            margin: 12px 0;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .flash.error {
            background: #fde8e8;
            color: #9b1c1c;
        }
        .flash.success {
            background: #def7ec;
            color: #03543f;
        }
    </style>
</head>
<body>
    <a href="home.php" class="btn-retour">&#8592; Retour</a>

    <div class="login-page">
        <div class="login-box">
            <div class="logo">Healthcare</div>
            <p class="login-subtitle">Votre plateforme de sante numerique</p>

            <div class="login-tabs">
                <div class="login-tab <?= $activeRole === 'patient' ? 'active' : '' ?>" onclick="switchTab('patient', this)">Patient</div>
                <div class="login-tab <?= $activeRole === 'medecin' ? 'active' : '' ?>" onclick="switchTab('medecin', this)">Medecin</div>
                <div class="login-tab <?= $activeRole === 'admin' ? 'active' : '' ?>" onclick="switchTab('admin', this)">Admin</div>
            </div>

            <?php if ($flashMessage !== ''): ?>
                <div class="flash <?= htmlspecialchars($flashType, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($flashMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form id="form-patient" class="tab-form <?= $activeRole === 'patient' ? 'active' : '' ?>" method="post" action="index.php">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role" value="patient">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" name="email" type="email" placeholder="nom@gmail.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" name="mot_de_passe" type="password" placeholder="********" required>
                </div>
                <button class="btn-submit" type="submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte" onclick="window.location.href='inscription.php?role=patient'">Creer un compte</span>
                </p>
            </form>

            <form id="form-medecin" class="tab-form <?= $activeRole === 'medecin' ? 'active' : '' ?>" method="post" action="index.php">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role" value="medecin">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" name="email" type="email" placeholder="nom@gmail.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Numero d'ordre medical</label>
                    <input class="form-input" name="numero_ordre" type="text" placeholder="Ex: CM-12345" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" name="mot_de_passe" type="password" placeholder="********" required>
                </div>
                <button class="btn-submit" type="submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte" onclick="window.location.href='inscription.php?role=medecin'">Creer un compte</span>
                </p>
            </form>

            <form id="form-admin" class="tab-form <?= $activeRole === 'admin' ? 'active' : '' ?>" method="post" action="index.php">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role" value="admin">
                <div class="form-group">
                    <label class="form-label">Identifiant admin</label>
                    <input class="form-input" name="email" type="email" placeholder="admin@healthcare.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" name="mot_de_passe" type="password" placeholder="********" required>
                </div>
                <button class="btn-submit" type="submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte" onclick="window.location.href='inscription.php?role=admin'">Creer un compte</span>
                </p>
            </form>
        </div>
    </div>

    <script>
        var roleActif = <?= json_encode($activeRole) ?>;

        function switchTab(role, el) {
            roleActif = role;
            document.querySelectorAll('.tab-form').forEach(function(form) {
                form.classList.remove('active');
            });
            document.querySelectorAll('.login-tab').forEach(function(tab) {
                tab.classList.remove('active');
            });
            document.getElementById('form-' + role).classList.add('active');
            el.classList.add('active');
        }
    </script>
</body>
</html>
