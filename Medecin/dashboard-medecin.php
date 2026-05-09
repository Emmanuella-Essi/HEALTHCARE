<?php
$page = __DIR__ . '/dashboard-medecin/dashboard-medecin.html';

if (!is_file($page)) {
    http_response_code(404);
    echo 'Page medecin introuvable.';
    exit;
}

$html = file_get_contents($page);

// Les assets de la page originale sont relatifs a son sous-dossier.
// Le <base> permet d'exposer une entree PHP directe dans /Medecin.
$html = str_replace('<head>', '<head>' . PHP_EOL . '    <base href="dashboard-medecin/">', $html);

echo $html;
