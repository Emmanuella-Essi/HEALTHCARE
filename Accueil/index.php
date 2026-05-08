 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare - Connexion</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        .tab-form {
            display: none;
        }
        .tab-form.active {
            display: block;
        }
        .login-tab.active {
            background-color: #1a3a5c;
            color: white;
            border-radius: 6px;
        }
    </style>
</head>
<body>


    <a href="home.php" class="btn-retour">&#8592; Retour</a>

    <div class="login-page">
        <div class="login-box">

  
            <div class="logo">Healthcare</div>
            <p class="login-subtitle">Votre plateforme de santé numérique</p>

            <div class="login-tabs">
                <div class="login-tab active" onclick="switchTab('patient', this)">Patient</div>
                <div class="login-tab" onclick="switchTab('medecin', this)">Médecin</div>
                <div class="login-tab" onclick="switchTab('admin', this)">Admin</div>
            </div>


            <div id="form-patient" class="tab-form active">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" type="email" placeholder="nom&prenom@gmail.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password" >
                </div>
                <button class="btn-submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte"
                          onclick="window.location.href='inscription.php?role=patient'">
                        Créer un compte
                    </span>
                </p>
            </div>

            <div id="form-medecin" class="tab-form">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" type="email" placeholder="nom&prenom@gmail.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Id medecin</label>
                    <input class="form-input" type="text"">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password" placeholder="••••••••">
                </div>
                <button class="btn-submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte"
                          onclick="window.location.href='inscription.php?role=medecin'">
                        Créer un compte
                    </span>
                </p>
            </div>

            <div id="form-admin" class="tab-form">
                <div class="form-group">
                    <label class="form-label">Identifiant admin</label>
                    <input class="form-input" type="text" placeholder="admin@healthcare.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password">
                </div>
                <button class="btn-submit">Se connecter</button>
            </div>

        </div>
    </div>

  
    <script>
        function switchTab(role, el) {

            var forms = document.querySelectorAll('.tab-form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].classList.remove('active');
            }

            var tabs = document.querySelectorAll('.login-tab');
            for (var j = 0; j < tabs.length; j++) {
                tabs[j].classList.remove('active');
            }


            document.getElementById('form-' + role).classList.add('active');

            el.classList.add('active');
        }
    </script>

</body>
</html>





<?php
// index.php  —  Point d'entrée de l'API (JSON)
//
// IMPORTANT: ce fichier contient aussi une page HTML. Pour éviter les corruptions JSON,
// on ne traite que les routes API.

// Si on affiche juste l'UI (connexion), ne pas exécuter le routeur API.
// L'API est accessible via /api/... (par ex: Accueil/index.php/api/auth/login)
if (php_sapi_name() !== 'cli') {
    $pathInfo = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    if (stripos($pathInfo, '/api/') === false) {
        exit;
    }
}

require_once __DIR__ . '/../BD/config/database.php';
require_once __DIR__ . '/../BD/middleware/auth.php';
require_once __DIR__ . '/../BD/utils/Response.php';
require_once __DIR__ . '/../BD/utils/Validator.php';
require_once __DIR__ . '/../BD/Controllers/AuthControllers.php';
require_once __DIR__ . '/../BD/Controllers/VaccinationControllers.php';
require_once __DIR__ . '/../BD/Controllers/DocumentController.php';
// (typo historique) : require_once __DIR__ . '/../BD/Controllers/MessageControllers.pho';
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
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim(preg_replace('#/+#', '/', $uri), '/');
$segments = array_values(array_filter(explode('/', $uri)));

// On cherche le segment "api" puis on route après.
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
            ['POST', 'login']    => AuthController::login(),
            ['POST', 'logout']   => AuthController::logout(),
            ['GET',  'me']       => AuthController::me(),
            default => Response::error('Route inconnue', 404),
        };
    }
    elseif ($s0 === 'vaccins' && $method === 'GET') {
        VaccinationController::listVaccins();
    }
    elseif ($s0 === 'patients' && $s2 === 'vaccinations') {
        match($method) {
            'GET'  => VaccinationController::carnetPatient($s1),
            'POST' => VaccinationController::ajouter($s1),
            default => Response::error('Méthode non autorisée', 405),
        };
    }
    elseif ($s0 === 'patients' && $s2 === 'rappels') {
        VaccinationController::rappels($s1);
    }
    elseif ($s0 === 'patients' && $s2 === 'documents') {
        match($method) {
            'GET'  => DocumentController::liste($s1),
            'POST' => DocumentController::upload($s1),
            default => Response::error('Méthode non autorisée', 405),
        };
    }
    elseif ($s0 === 'vaccinations' && $s1 && $method === 'DELETE') {
        VaccinationController::supprimer($s1);
    }
    elseif ($s0 === 'vaccinations' && ($segments[1] ?? '') === 'stats') {
        VaccinationController::stats();
    }
    elseif ($s0 === 'consultations') {
        Response::error('Consultations non couvert dans ce fichier', 501);
    }
    elseif ($s0 === 'medecins') {
        Response::error('Medecins non couvert dans ce fichier', 501);
    }
    elseif ($s0 === 'documents' && $s1 && $method === 'DELETE') {
        DocumentController::supprimer($s1);
    }
    else {
        Response::error('Route introuvable', 404);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    Response::error('Erreur base de données', 500);
} catch (Exception $e) {
    error_log($e->getMessage());
    Response::error('Erreur serveur', 500);
}

