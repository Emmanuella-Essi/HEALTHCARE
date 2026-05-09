<?php
/**
 * agenda.php — API Backend des rendez-vous médecin
 * ──────────────────────────────────────────────────
 * Méthodes HTTP supportées :
 *   GET    → Récupère les RDV (par mois, par jour, ou un seul)
 *   POST   → Crée un nouveau RDV
 *   PUT    → Modifie un RDV existant
 *   DELETE → Supprime un RDV
 *
 * URL exemples :
 *   GET  api/agenda.php?year=2025&month=5
 *   GET  api/agenda.php?date=2025-05-08
 *   GET  api/agenda.php?id=12
 *   POST api/agenda.php     (body JSON)
 *   PUT  api/agenda.php     (body JSON avec id)
 *   DELETE api/agenda.php?id=12
 * ──────────────────────────────────────────────────
 */

/* ── En-têtes HTTP obligatoires pour une API JSON ── */
header('Content-Type: application/json; charset=UTF-8');  /* Réponse en JSON */
header('Access-Control-Allow-Origin: *');                 /* Autorise CORS (dev) */
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

/* Réponse rapide aux requêtes OPTIONS (preflight CORS) */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);   /* OK sans corps */
    exit;
}

/* ── Connexion à la base de données ── */
require_once '../config/db.php';   /* Fournit $pdo (PDO) */

/* ── Vérification de session médecin ── */
session_start();                   /* Démarre la session PHP */

/* Si le médecin n'est pas connecté → erreur 401 */
if (!isset($_SESSION['medecin_id'])) {
    http_response_code(401);       /* Non autorisé */
    echo json_encode([
        'success' => false,
        'message' => 'Non autorisé — veuillez vous connecter'
    ]);
    exit;
}

/** ID du médecin connecté (issu de la session) */
$medecinId = (int) $_SESSION['medecin_id'];

/* ── Méthode HTTP de la requête ── */
$method = $_SERVER['REQUEST_METHOD'];


/* ============================================================
   ROUTEUR — Dispatch selon la méthode HTTP
   ============================================================ */
switch ($method) {

    case 'GET':
        handleGet($pdo, $medecinId);    /* Lecture */
        break;

    case 'POST':
        handlePost($pdo, $medecinId);   /* Création */
        break;

    case 'PUT':
        handlePut($pdo, $medecinId);    /* Modification */
        break;

    case 'DELETE':
        handleDelete($pdo, $medecinId); /* Suppression */
        break;

    default:
        /* Méthode non supportée */
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => "Méthode HTTP '{$method}' non supportée"
        ]);
}


/* ============================================================
   FONCTION — GET : Lire les rendez-vous
   ============================================================ */
/**
 * Récupère les RDV du médecin selon les paramètres GET
 *
 * @param PDO    $pdo       Instance PDO
 * @param int    $medecinId ID du médecin connecté
 */
function handleGet(PDO $pdo, int $medecinId): void
{
    /* ── Cas 1 : récupérer un RDV par son ID ── */
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];    /* Cast en int (sécurité) */

        $stmt = $pdo->prepare('
            SELECT
                r.id,
                r.patient_id      AS patientId,
                CONCAT(p.prenom, " ", p.nom) AS patientNom,
                r.type,
                r.date,
                r.heure,
                r.duree,
                r.motif,
                r.created_at      AS createdAt
            FROM rendez_vous r
            INNER JOIN patients p ON p.id = r.patient_id
            WHERE r.id = :id
              AND r.medecin_id = :medecinId
        ');

        $stmt->execute([
            ':id'        => $id,
            ':medecinId' => $medecinId
        ]);

        $rdv = $stmt->fetch(PDO::FETCH_ASSOC);  /* Un seul résultat */

        if (!$rdv) {
            /* RDV inexistant ou n'appartient pas au médecin */
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Rendez-vous introuvable']);
            return;
        }

        echo json_encode(['success' => true, 'rdv' => $rdv]);
        return;
    }

    /* ── Cas 2 : récupérer les RDV d'un jour précis ── */
    if (isset($_GET['date'])) {
        $date = sanitizeDate($_GET['date']);   /* Valide et nettoie la date */

        if (!$date) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Format de date invalide (attendu : YYYY-MM-DD)']);
            return;
        }

        $stmt = $pdo->prepare('
            SELECT
                r.id,
                r.patient_id                  AS patientId,
                CONCAT(p.prenom, " ", p.nom)  AS patientNom,
                r.type,
                r.date,
                r.heure,
                r.duree,
                r.motif
            FROM rendez_vous r
            INNER JOIN patients p ON p.id = r.patient_id
            WHERE r.date = :date
              AND r.medecin_id = :medecinId
            ORDER BY r.heure ASC
        ');

        $stmt->execute([':date' => $date, ':medecinId' => $medecinId]);
        $rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);  /* Tableau de RDV */

        echo json_encode(['success' => true, 'rdv' => $rdvs, 'count' => count($rdvs)]);
        return;
    }

    /* ── Cas 3 : récupérer les RDV d'un mois ── */
    if (isset($_GET['year'], $_GET['month'])) {
        $year  = (int) $_GET['year'];
        $month = (int) $_GET['month'];

        /* Validation : mois entre 1 et 12, année raisonnable */
        if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Année ou mois invalide']);
            return;
        }

        /* Construit les bornes du mois : premier et dernier jour */
        $dateStart = sprintf('%04d-%02d-01', $year, $month);
        $dateEnd   = date('Y-m-t', strtotime($dateStart));  /* dernier jour du mois */

        $stmt = $pdo->prepare('
            SELECT
                r.id,
                r.patient_id                  AS patientId,
                CONCAT(p.prenom, " ", p.nom)  AS patientNom,
                r.type,
                r.date,
                r.heure,
                r.duree,
                r.motif
            FROM rendez_vous r
            INNER JOIN patients p ON p.id = r.patient_id
            WHERE r.date BETWEEN :dateStart AND :dateEnd
              AND r.medecin_id = :medecinId
            ORDER BY r.date ASC, r.heure ASC
        ');

        $stmt->execute([
            ':dateStart' => $dateStart,
            ':dateEnd'   => $dateEnd,
            ':medecinId' => $medecinId
        ]);

        $rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Statistiques par type pour la légende */
        $stats = ['consultation' => 0, 'urgence' => 0, 'suivi' => 0, 'tele' => 0];
        foreach ($rdvs as $r) {
            if (isset($stats[$r['type']])) {
                $stats[$r['type']]++;
            }
        }

        echo json_encode([
            'success' => true,
            'rdv'     => $rdvs,
            'count'   => count($rdvs),
            'stats'   => $stats,        /* Compteurs par type */
            'year'    => $year,
            'month'   => $month
        ]);
        return;
    }

    /* ── Cas 4 : aucun paramètre → RDV d'aujourd'hui ── */
    $today = date('Y-m-d');   /* Date du serveur */

    $stmt = $pdo->prepare('
        SELECT
            r.id,
            r.patient_id                  AS patientId,
            CONCAT(p.prenom, " ", p.nom)  AS patientNom,
            r.type,
            r.date,
            r.heure,
            r.duree,
            r.motif
        FROM rendez_vous r
        INNER JOIN patients p ON p.id = r.patient_id
        WHERE r.date = :today
          AND r.medecin_id = :medecinId
        ORDER BY r.heure ASC
    ');

    $stmt->execute([':today' => $today, ':medecinId' => $medecinId]);
    $rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'rdv'     => $rdvs,
        'count'   => count($rdvs),
        'date'    => $today
    ]);
}


/* ============================================================
   FONCTION — POST : Créer un rendez-vous
   ============================================================ */
/**
 * Insère un nouveau rendez-vous en base
 *
 * Corps JSON attendu :
 * {
 *   "patientId"   : 5,
 *   "type"        : "consultation",
 *   "date"        : "2025-05-15",
 *   "heure"       : "10:30",
 *   "duree"       : 30,
 *   "motif"       : "Bilan annuel"
 * }
 */
function handlePost(PDO $pdo, int $medecinId): void
{
    /* Lit et décode le corps JSON de la requête */
    $body = json_decode(file_get_contents('php://input'), true);

    /* Vérifie que le JSON est valide */
    if (!$body) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Corps JSON invalide ou manquant']);
        return;
    }

    /* ── Validation des champs obligatoires ── */
    $errors = [];

    if (empty($body['patientId']))            $errors[] = 'patientId est requis';
    if (empty($body['type']))                 $errors[] = 'type est requis';
    if (empty($body['date']))                 $errors[] = 'date est requise';
    if (empty($body['heure']))                $errors[] = 'heure est requise';

    /* Valide le type (liste blanche) */
    $typesValides = ['consultation', 'urgence', 'suivi', 'tele'];
    if (!empty($body['type']) && !in_array($body['type'], $typesValides, true)) {
        $errors[] = "type invalide — valeurs acceptées : " . implode(', ', $typesValides);
    }

    /* Valide la date */
    if (!empty($body['date']) && !sanitizeDate($body['date'])) {
        $errors[] = 'Format de date invalide (attendu : YYYY-MM-DD)';
    }

    /* S'il y a des erreurs → renvoie les erreurs */
    if (!empty($errors)) {
        http_response_code(422);   /* Unprocessable Entity */
        echo json_encode(['success' => false, 'errors' => $errors]);
        return;
    }

    /* ── Vérifie que le patient appartient au médecin ── */
    $stmtCheck = $pdo->prepare('SELECT id FROM patients WHERE id = :pid AND medecin_id = :mid');
    $stmtCheck->execute([':pid' => (int)$body['patientId'], ':mid' => $medecinId]);
    if (!$stmtCheck->fetch()) {
        http_response_code(403);   /* Interdit */
        echo json_encode(['success' => false, 'message' => 'Patient non autorisé']);
        return;
    }

    /* ── Insertion en base ── */
    $stmt = $pdo->prepare('
        INSERT INTO rendez_vous
            (medecin_id, patient_id, type, date, heure, duree, motif, created_at)
        VALUES
            (:medecinId, :patientId, :type, :date, :heure, :duree, :motif, NOW())
    ');

    $stmt->execute([
        ':medecinId' => $medecinId,
        ':patientId' => (int)   $body['patientId'],
        ':type'      =>         $body['type'],
        ':date'      =>         sanitizeDate($body['date']),
        ':heure'     =>         $body['heure'],
        ':duree'     => (int)  ($body['duree'] ?? 30),   /* 30 min par défaut */
        ':motif'     =>         $body['motif'] ?? ''
    ]);

    $newId = (int) $pdo->lastInsertId();   /* ID de la ligne insérée */

    http_response_code(201);   /* Created */
    echo json_encode([
        'success' => true,
        'message' => 'Rendez-vous créé avec succès',
        'id'      => $newId
    ]);
}


/* ============================================================
   FONCTION — PUT : Modifier un rendez-vous
   ============================================================ */
/**
 * Met à jour un rendez-vous existant
 *
 * Corps JSON attendu : mêmes champs que POST + "id"
 */
function handlePut(PDO $pdo, int $medecinId): void
{
    $body = json_decode(file_get_contents('php://input'), true);

    if (!$body || empty($body['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Corps JSON invalide ou id manquant']);
        return;
    }

    $rdvId = (int) $body['id'];

    /* Vérifie que le RDV appartient au médecin */
    $stmtCheck = $pdo->prepare('SELECT id FROM rendez_vous WHERE id = :id AND medecin_id = :mid');
    $stmtCheck->execute([':id' => $rdvId, ':mid' => $medecinId]);
    if (!$stmtCheck->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Rendez-vous introuvable ou non autorisé']);
        return;
    }

    /* ── Construit la requête UPDATE dynamiquement ── */
    $fields = [];    /* Fragments SQL des colonnes à mettre à jour */
    $params = [':id' => $rdvId, ':medecinId' => $medecinId];

    /* Met à jour uniquement les champs fournis dans le corps */
    if (isset($body['type']))      { $fields[] = 'type = :type';   $params[':type']   = $body['type']; }
    if (isset($body['date']))      { $fields[] = 'date = :date';   $params[':date']   = sanitizeDate($body['date']); }
    if (isset($body['heure']))     { $fields[] = 'heure = :heure'; $params[':heure']  = $body['heure']; }
    if (isset($body['duree']))     { $fields[] = 'duree = :duree'; $params[':duree']  = (int)$body['duree']; }
    if (isset($body['motif']))     { $fields[] = 'motif = :motif'; $params[':motif']  = $body['motif']; }
    if (isset($body['patientId'])) { $fields[] = 'patient_id = :patientId'; $params[':patientId'] = (int)$body['patientId']; }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Aucun champ à mettre à jour']);
        return;
    }

    /* Exécute la mise à jour */
    $sql = 'UPDATE rendez_vous SET ' . implode(', ', $fields) . ' WHERE id = :id AND medecin_id = :medecinId';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true, 'message' => 'Rendez-vous modifié avec succès']);
}


/* ============================================================
   FONCTION — DELETE : Supprimer un rendez-vous
   ============================================================ */
/**
 * Supprime un rendez-vous de la base
 * URL : DELETE api/agenda.php?id=12
 */
function handleDelete(PDO $pdo, int $medecinId): void
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Paramètre id requis']);
        return;
    }

    $rdvId = (int) $_GET['id'];

    /* Vérifie que le RDV appartient au médecin (sécurité) */
    $stmtCheck = $pdo->prepare('SELECT id FROM rendez_vous WHERE id = :id AND medecin_id = :mid');
    $stmtCheck->execute([':id' => $rdvId, ':mid' => $medecinId]);
    if (!$stmtCheck->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Rendez-vous introuvable ou non autorisé']);
        return;
    }

    /* Suppression */
    $stmt = $pdo->prepare('DELETE FROM rendez_vous WHERE id = :id AND medecin_id = :mid');
    $stmt->execute([':id' => $rdvId, ':mid' => $medecinId]);

    echo json_encode(['success' => true, 'message' => 'Rendez-vous supprimé avec succès']);
}


/* ============================================================
   UTILITAIRE — Valide et nettoie une date YYYY-MM-DD
   ============================================================ */
/**
 * Valide une chaîne de date au format YYYY-MM-DD
 *
 * @param  string $date - Date brute reçue
 * @return string|false - Date nettoyée ou false si invalide
 */
function sanitizeDate(string $date): string|false
{
    /* Vérifie le format via regex */
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;   /* Format incorrect */
    }

    /* Vérifie que la date est réellement valide (ex: pas 2025-02-30) */
    [$year, $month, $day] = explode('-', $date);
    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        return false;   /* Date impossible */
    }

    return $date;   /* Date valide → retourne telle quelle */
}