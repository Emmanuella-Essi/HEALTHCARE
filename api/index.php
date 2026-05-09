<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../BD/Controllers/PatientController.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $path);
$apiIndex = array_search('api', $segments, true);
$resource = $apiIndex !== false ? ($segments[$apiIndex + 1] ?? '') : ($_GET['resource'] ?? '');
$id = $apiIndex !== false ? ($segments[$apiIndex + 2] ?? null) : ($_GET['id'] ?? null);

if ($resource === 'index.php') {
    $resource = $segments[$apiIndex + 2] ?? ($_GET['resource'] ?? '');
    $id = $segments[$apiIndex + 3] ?? ($_GET['id'] ?? null);
}

try {
    if ($resource === '') {
        $resource = $_GET['resource'] ?? '';
    }

    if ($resource === 'patients') {
        if ($method === 'GET' && $id) {
            PatientController::show((int) $id);
        }

        if ($method === 'GET') {
            PatientController::index();
        }

        if ($method === 'POST') {
            PatientController::store();
        }
    }

    http_response_code(404);
    echo json_encode(['succes' => false, 'erreur' => 'Route API introuvable']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['succes' => false, 'erreur' => $e->getMessage()]);
}
