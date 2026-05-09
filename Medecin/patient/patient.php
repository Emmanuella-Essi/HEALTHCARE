<?php
$page = __DIR__ . '/patient.html';

if (!is_file($page)) {
    http_response_code(404);
    echo 'Page patients medecin introuvable.';
    exit;
}

echo file_get_contents($page);
