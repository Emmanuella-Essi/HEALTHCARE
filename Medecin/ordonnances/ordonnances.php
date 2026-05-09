<?php
/* =====================================================
   ORDONNANCES.PHP — BACKEND API ORDONNANCES
   Dashboard Médecin — Module Ordonnances Numériques
   API REST : GET/POST avec PDO sécurisé
   Chaque ligne est commentée pour la compréhension
   ===================================================== */

/* ─────────────────────────────────────────────────────
   CONFIGURATION INITIALE
───────────────────────────────────────────────────── */

/* Affichage des erreurs PHP (désactiver en production) */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* Démarrage de la session PHP (nécessaire pour l'authentification) */
session_start();

/* ─── En-têtes HTTP ─── */
/* Indique que la réponse est du JSON en UTF-8 */
header('Content-Type: application/json; charset=UTF-8');

/* Autorise les requêtes depuis n'importe quel domaine (CORS) */
header('Access-Control-Allow-Origin: *');

/* Méthodes HTTP acceptées par cette API */
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');

/* En-têtes HTTP autorisés dans les requêtes */
header('Access-Control-Allow-Headers: Content-Type, Authorization');

/* Réponse rapide aux requêtes OPTIONS (pré-vol CORS) */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);   /* 204 No Content */
    exit;
}

/* ─────────────────────────────────────────────────────
   1. CONFIGURATION BASE DE DONNÉES
───────────────────────────────────────────────────── */

/* Constantes de connexion MySQL */
define('DB_HOST',    'localhost');    /* Serveur de base de données */
define('DB_NAME',    'medicare_db'); /* Nom de la base de données */
define('DB_USER',    'root');         /* Utilisateur MySQL */
define('DB_PASS',    '');             /* Mot de passe MySQL (à sécuriser en prod) */
define('DB_CHARSET', 'utf8mb4');      /* Encodage : supporte les emojis et caractères spéciaux */

/**
 * Retourne une instance PDO de connexion à la base de données
 * Utilise le pattern Singleton pour éviter plusieurs connexions
 *
 * @return PDO Instance de connexion
 * @throws PDOException En cas d'échec
 */
function getDB(): PDO
{
    /* Variable statique : conservée entre les appels */
    static $pdo = null;

    /* Si déjà connecté, retourne la connexion existante */
    if ($pdo !== null) return $pdo;

    /* Construction du DSN (Data Source Name) */
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        DB_HOST,     /* Hôte */
        DB_NAME,     /* Base */
        DB_CHARSET   /* Encodage */
    );

    /* Options PDO recommandées */
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   /* Lance des exceptions sur erreur SQL */
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          /* Résultats sous forme de tableaux associatifs */
        PDO::ATTR_EMULATE_PREPARES   => false,                     /* Utilise les vraies requêtes préparées */
        PDO::MYSQL_ATTR_FOUND_ROWS   => true,                      /* Compte les lignes matchées (pas seulement modifiées) */
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        /* En cas d'erreur de connexion, retourne une erreur JSON */
        sendError('Connexion BDD échouée : ' . $e->getMessage(), 500);
        exit;
    }
}

/* ─────────────────────────────────────────────────────
   2. HELPERS RÉPONSE JSON
───────────────────────────────────────────────────── */

/**
 * Envoie une réponse JSON de succès
 *
 * @param mixed  $data  Données à inclure dans la réponse
 * @param int    $code  Code HTTP (200 par défaut)
 */
function sendSuccess($data, int $code = 200): void
{
    http_response_code($code);   /* Définit le code HTTP */
    echo json_encode([
        'success'   => true,                        /* Indicateur de succès */
        'data'      => $data,                       /* Données retournées */
        'timestamp' => date('c'),                   /* Horodatage ISO 8601 */
        'version'   => '1.0'                        /* Version de l'API */
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); /* Encodage lisible */
    exit;
}

/**
 * Envoie une réponse JSON d'erreur
 *
 * @param string $message Message d'erreur descriptif
 * @param int    $code    Code HTTP (400 par défaut)
 */
function sendError(string $message, int $code = 400): void
{
    http_response_code($code);   /* Code HTTP d'erreur */
    echo json_encode([
        'success'   => false,
        'error'     => $message,
        'timestamp' => date('c'),
        'code'      => $code
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/* ─────────────────────────────────────────────────────
   3. SÉCURITÉ & VALIDATION
───────────────────────────────────────────────────── */

/**
 * Vérifie que le médecin est authentifié via la session
 * Bloque l'accès si la session est absente ou expirée
 */
function requireAuth(): void
{
    /* Vérifie la présence de l'ID médecin en session */
    if (empty($_SESSION['medecin_id'])) {
        sendError('Non authentifié. Connectez-vous.', 401); /* 401 Unauthorized */
    }

    /* Vérifie que la session n'a pas expiré (30 min d'inactivité) */
    if (isset($_SESSION['last_activity'])) {
        $inactif = time() - $_SESSION['last_activity'];     /* Secondes depuis dernière activité */
        if ($inactif > 1800) {                              /* 1800 secondes = 30 minutes */
            session_destroy();                               /* Détruit la session */
            sendError('Session expirée. Reconnectez-vous.', 401);
        }
    }

    /* Met à jour le timestamp de dernière activité */
    $_SESSION['last_activity'] = time();
}

/**
 * Nettoie et sécurise une chaîne de caractères
 * Protection contre XSS et injections HTML
 *
 * @param  string $val Valeur brute à nettoyer
 * @return string      Valeur nettoyée
 */
function clean(string $val): string
{
    $val = trim($val);                                         /* Supprime espaces début/fin */
    $val = strip_tags($val);                                   /* Supprime les balises HTML */
    $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');        /* Encode les caractères spéciaux */
    return $val;
}

/**
 * Valide une date au format YYYY-MM-DD
 *
 * @param  string $date La date à valider
 * @return bool         true si valide, false sinon
 */
function isDate(string $date): bool
{
    /* Vérifie le format avec regex */
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return false;

    /* Décompose et vérifie que la date existe réellement */
    [$y, $m, $d] = explode('-', $date);
    return checkdate((int)$m, (int)$d, (int)$y);
}

/**
 * Valide que le statut est dans les valeurs autorisées
 *
 * @param  string $statut Le statut à valider
 * @return bool           true si valide
 */
function isValidStatut(string $statut): bool
{
    return in_array($statut, ['active', 'expiree', 'renouvelee', 'annulee'], true);
}

/* ─────────────────────────────────────────────────────
   4. ROUTEUR PRINCIPAL
───────────────────────────────────────────────────── */

/* Récupère la méthode HTTP de la requête courante */
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* Récupère le paramètre d'action depuis l'URL : ?action=xxx */
$action = trim($_GET['action'] ?? '');

/* Décommente pour activer l'authentification en production */
/* requireAuth(); */

/* ── Routage selon la méthode + action ── */
switch (true) {

    /* ── GET : Récupère la liste paginée des ordonnances ── */
    case ($method === 'GET' && $action === 'list'):
        actionList();
        break;

    /* ── GET : Détail d'une ordonnance par ID ── */
    case ($method === 'GET' && $action === 'detail'):
        actionDetail((int)($_GET['id'] ?? 0));
        break;

    /* ── GET : Statistiques globales ── */
    case ($method === 'GET' && $action === 'stats'):
        actionStats();
        break;

    /* ── GET : Liste des patients pour le select ── */
    case ($method === 'GET' && $action === 'patients'):
        actionPatients();
        break;

    /* ── GET : Génération PDF d'une ordonnance ── */
    case ($method === 'GET' && $action === 'pdf'):
        actionGeneratePDF((int)($_GET['id'] ?? 0));
        break;

    /* ── POST : Créer une nouvelle ordonnance ── */
    case ($method === 'POST' && $action === 'add'):
        actionAdd();
        break;

    /* ── POST : Modifier une ordonnance existante ── */
    case ($method === 'POST' && $action === 'update'):
        actionUpdate();
        break;

    /* ── POST : Supprimer une ordonnance ── */
    case ($method === 'POST' && $action === 'delete'):
        actionDelete();
        break;

    /* ── POST : Envoyer une ordonnance par email ── */
    case ($method === 'POST' && $action === 'send'):
        actionSendEmail();
        break;

    /* ── POST : Changer le statut d'une ordonnance ── */
    case ($method === 'POST' && $action === 'statut'):
        actionChangeStatut();
        break;

    /* ── Route inconnue ── */
    default:
        sendError('Action inconnue : ' . htmlspecialchars($action), 404);
}

/* ─────────────────────────────────────────────────────
   5. ACTION : LISTE DES ORDONNANCES
───────────────────────────────────────────────────── */

/**
 * Récupère la liste filtrée et paginée des ordonnances
 *
 * Paramètres GET acceptés :
 *   search   → Texte libre (n°, patient, diagnostic, médicament)
 *   statut   → Filtre par statut (active|expiree|renouvelee|annulee)
 *   patient  → Nom du patient
 *   periode  → Nombre de jours (7|30|90|365)
 *   page     → Page courante (défaut: 1)
 *   per_page → Lignes par page (défaut: 8, max: 50)
 *   sort_col → Colonne de tri (numero|patient|date|statut)
 *   sort_dir → Direction (asc|desc)
 */
function actionList(): void
{
    $pdo = getDB();   /* Connexion base de données */

    /* ── Lecture et nettoyage des paramètres GET ── */
    $search  = clean($_GET['search']  ?? '');        /* Texte de recherche */
    $statut  = clean($_GET['statut']  ?? '');        /* Filtre statut */
    $patient = clean($_GET['patient'] ?? '');        /* Filtre patient */
    $periode = max(0, (int)($_GET['periode'] ?? 0)); /* Période en jours */

    /* Pagination */
    $page     = max(1, (int)($_GET['page']     ?? 1));  /* Minimum page 1 */
    $per_page = min(50, max(1, (int)($_GET['per_page'] ?? 8))); /* Clampé entre 1 et 50 */
    $offset   = ($page - 1) * $per_page;               /* Décalage SQL */

    /* Tri : vérifie que la colonne est autorisée */
    $sort_map = [
        'numero'  => 'o.numero',     /* Tri par numéro */
        'patient' => 'p.nom',        /* Tri par nom patient */
        'date'    => 'o.date_create',/* Tri par date */
        'statut'  => 'o.statut'      /* Tri par statut */
    ];
    $sort_col = $sort_map[$_GET['sort_col'] ?? ''] ?? 'o.date_create'; /* Colonne sécurisée */
    $sort_dir = strtoupper($_GET['sort_dir'] ?? '') === 'ASC' ? 'ASC' : 'DESC'; /* Direction */

    /* ── Construction des conditions WHERE dynamiques ── */
    $conditions = ['1=1'];   /* Condition toujours vraie (base) */
    $params     = [];        /* Paramètres de la requête préparée */

    /* Filtre texte : recherche dans plusieurs champs */
    if ($search !== '') {
        $conditions[] = '(o.numero LIKE :s1 OR p.nom LIKE :s2 OR p.prenom LIKE :s3 OR o.diagnostic LIKE :s4)';
        $like = "%$search%";              /* Wildcards autour */
        $params[':s1'] = $like;
        $params[':s2'] = $like;
        $params[':s3'] = $like;
        $params[':s4'] = $like;
    }

    /* Filtre statut */
    if ($statut !== '' && isValidStatut($statut)) {
        $conditions[] = 'o.statut = :statut';
        $params[':statut'] = $statut;
    }

    /* Filtre patient (par nom) */
    if ($patient !== '') {
        $conditions[] = 'CONCAT(p.prenom, " ", p.nom) LIKE :patient'; /* Prénom + Nom */
        $params[':patient'] = "%$patient%";
    }

    /* Filtre période : ordonnances créées dans les X derniers jours */
    if ($periode > 0) {
        $conditions[] = 'o.date_create >= DATE_SUB(CURDATE(), INTERVAL :periode DAY)';
        $params[':periode'] = $periode;
    }

    /* Construction de la clause WHERE complète */
    $where = implode(' AND ', $conditions);

    /* ── Requête de comptage total ── */
    $sqlCount = "
        SELECT COUNT(*)
        FROM ordonnances o
        INNER JOIN patients p ON p.id = o.patient_id
        WHERE $where
    ";
    $stmtCount = $pdo->prepare($sqlCount);
    $stmtCount->execute($params);
    $total = (int)$stmtCount->fetchColumn();  /* Nombre total de résultats */

    /* ── Requête principale avec pagination ── */
    $sql = "
        SELECT
            o.id,                                          -- ID ordonnance
            o.numero,                                      -- N° formaté
            o.diagnostic,                                  -- Motif
            o.date_create,                                 -- Date création
            o.date_expiration,                             -- Date expiration
            o.instructions,                                -- Instructions patient
            o.statut,                                      -- Statut
            o.envoye,                                      -- Envoyée par email (0/1)
            o.created_at,                                  -- Timestamp création
            p.id        AS patient_id,                     -- ID patient
            p.nom       AS patient_nom,                    -- Nom patient
            p.prenom    AS patient_prenom,                 -- Prénom patient
            p.age       AS patient_age,                    -- Âge patient
            p.photo     AS patient_photo                   -- Photo patient
        FROM ordonnances o
        INNER JOIN patients p ON p.id = o.patient_id
        WHERE $where
        ORDER BY $sort_col $sort_dir              -- Tri dynamique sécurisé
        LIMIT :limit OFFSET :offset               -- Pagination
    ";

    $stmt = $pdo->prepare($sql);

    /* Liaison des paramètres de filtre */
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }

    /* Liaison des paramètres de pagination (entiers obligatoires) */
    $stmt->bindValue(':limit',  $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,   PDO::PARAM_INT);

    $stmt->execute();
    $rows = $stmt->fetchAll();  /* Récupère toutes les lignes de la page */

    /* ── Récupère les médicaments pour chaque ordonnance ── */
    $ordonnances = [];
    foreach ($rows as $row) {
        /* Récupère les médicaments de cette ordonnance */
        $meds = getMedsByOrdId($pdo, (int)$row['id']);

        /* Formate l'objet ordonnance pour le frontend */
        $ordonnances[] = formatOrdonnance($row, $meds);
    }

    /* ── Envoi de la réponse ── */
    sendSuccess([
        'ordonnances' => $ordonnances,
        'pagination'  => [
            'total'    => $total,
            'page'     => $page,
            'per_page' => $per_page,
            'pages'    => (int)ceil($total / $per_page),
        ]
    ]);
}

/* ─────────────────────────────────────────────────────
   6. ACTION : DÉTAIL D'UNE ORDONNANCE
───────────────────────────────────────────────────── */

/**
 * Récupère le détail complet d'une ordonnance par son ID
 *
 * @param int $id ID de l'ordonnance
 */
function actionDetail(int $id): void
{
    if ($id <= 0) sendError('ID invalide.', 400);   /* Validation basique */

    $pdo = getDB();

    /* Récupère l'ordonnance avec les infos patient */
    $sql = "
        SELECT
            o.*,
            p.id     AS patient_id,
            p.nom    AS patient_nom,
            p.prenom AS patient_prenom,
            p.age    AS patient_age,
            p.photo  AS patient_photo,
            p.email  AS patient_email,
            p.tel    AS patient_tel
        FROM ordonnances o
        INNER JOIN patients p ON p.id = o.patient_id
        WHERE o.id = :id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();

    /* Ordonnance introuvable */
    if (!$row) sendError('Ordonnance introuvable.', 404);

    /* Récupère les médicaments associés */
    $meds = getMedsByOrdId($pdo, $id);

    /* Envoie le détail complet */
    sendSuccess(formatOrdonnance($row, $meds, true));  /* true = inclut les infos contact patient */
}

/* ─────────────────────────────────────────────────────
   7. ACTION : STATISTIQUES
───────────────────────────────────────────────────── */

/**
 * Retourne les statistiques globales des ordonnances pour le médecin connecté
 */
function actionStats(): void
{
    $pdo = getDB();

    /* ID médecin (depuis la session en production) */
    $mid = 1; /* $_SESSION['medecin_id'] */

    /* Compte par statut en une seule requête GROUP BY */
    $stmtStatuts = $pdo->prepare("
        SELECT statut, COUNT(*) AS nb
        FROM ordonnances
        WHERE medecin_id = :mid
        GROUP BY statut
    ");
    $stmtStatuts->execute([':mid' => $mid]);

    /* Initialise tous les compteurs */
    $stats = ['active' => 0, 'expiree' => 0, 'renouvelee' => 0, 'annulee' => 0];

    foreach ($stmtStatuts->fetchAll() as $r) {
        if (array_key_exists($r['statut'], $stats)) {
            $stats[$r['statut']] = (int)$r['nb']; /* Remplit le bon compteur */
        }
    }

    /* Total général */
    $total = array_sum($stats);

    /* Nombre d'ordonnances envoyées par email */
    $stmtEnv = $pdo->prepare("SELECT COUNT(*) FROM ordonnances WHERE medecin_id = :mid AND envoye = 1");
    $stmtEnv->execute([':mid' => $mid]);
    $envoyees = (int)$stmtEnv->fetchColumn();

    /* Ordonnances expirant dans les 7 prochains jours */
    $stmtExpire = $pdo->prepare("
        SELECT COUNT(*)
        FROM ordonnances
        WHERE medecin_id = :mid
          AND statut = 'active'
          AND date_expiration BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ");
    $stmtExpire->execute([':mid' => $mid]);
    $expirant = (int)$stmtExpire->fetchColumn();

    /* Nouvelles ordonnances créées ce mois */
    $stmtMois = $pdo->prepare("
        SELECT COUNT(*)
        FROM ordonnances
        WHERE medecin_id = :mid
          AND MONTH(date_create) = MONTH(CURDATE())
          AND YEAR(date_create) = YEAR(CURDATE())
    ");
    $stmtMois->execute([':mid' => $mid]);
    $ce_mois = (int)$stmtMois->fetchColumn();

    sendSuccess([
        'total'      => $total,              /* Toutes les ordonnances */
        'active'     => $stats['active'],    /* Actives */
        'expiree'    => $stats['expiree'],   /* Expirées */
        'renouvelee' => $stats['renouvelee'],/* Renouvelées */
        'annulee'    => $stats['annulee'],   /* Annulées */
        'envoyees'   => $envoyees,           /* Envoyées par email */
        'expirant'   => $expirant,           /* Expirent dans 7 jours */
        'ce_mois'    => $ce_mois,            /* Créées ce mois */
    ]);
}

/* ─────────────────────────────────────────────────────
   8. ACTION : LISTE DES PATIENTS
───────────────────────────────────────────────────── */

/**
 * Retourne la liste des patients pour alimenter le select du formulaire
 */
function actionPatients(): void
{
    $pdo = getDB();

    /* Récupère les patients actifs triés par nom */
    $sql = "SELECT id, nom, prenom, age, photo FROM patients ORDER BY nom ASC, prenom ASC";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    /* Formate les données pour le frontend */
    $patients = array_map(fn($r) => [
        'id'     => (int)$r['id'],
        'nom'    => $r['prenom'] . ' ' . $r['nom'],          /* Prénom Nom */
        'age'    => (int)$r['age'],
        'avatar' => $r['photo']
            ? '/uploads/patients/' . $r['photo']              /* Photo réelle */
            : 'https://ui-avatars.com/api/?name=' . urlencode($r['prenom'] . ' ' . $r['nom']) . '&background=4A7FA7&color=fff&size=60'
    ], $rows);

    sendSuccess(['patients' => $patients, 'count' => count($patients)]);
}

/* ─────────────────────────────────────────────────────
   9. ACTION : CRÉER UNE ORDONNANCE
───────────────────────────────────────────────────── */

/**
 * Insère une nouvelle ordonnance et ses médicaments en base de données
 * Données attendues en corps de requête JSON (POST body)
 */
function actionAdd(): void
{
    /* Lecture du corps JSON de la requête */
    $body = json_decode(file_get_contents('php://input'), true);

    /* Validation : corps JSON doit exister */
    if (!is_array($body)) sendError('Corps JSON manquant ou invalide.', 400);

    /* ── Extraction et nettoyage des champs ── */
    $patient_id  = (int)($body['patient_id']   ?? 0);         /* ID patient */
    $diagnostic  = clean($body['diagnostic']   ?? '');        /* Diagnostic */
    $date_create = clean($body['date']         ?? '');        /* Date création */
    $date_exp    = clean($body['expiration']   ?? '');        /* Date expiration */
    $instructions= clean($body['instructions'] ?? '');        /* Instructions */
    $statut      = clean($body['statut']       ?? 'active'); /* Statut */
    $envoye      = !empty($body['envoye']) ? 1 : 0;           /* Booléen envoi email */
    $meds        = $body['medicaments'] ?? [];                /* Tableau médicaments */
    $medecin_id  = 1; /* $_SESSION['medecin_id'] */           /* ID médecin */

    /* ── Validations ── */
    if ($patient_id <= 0)        sendError('Patient invalide.', 400);
    if (empty($date_create))     sendError('La date est requise.', 400);
    if (!isDate($date_create))   sendError('Format de date invalide (YYYY-MM-DD).', 400);
    if (!isValidStatut($statut)) sendError('Statut invalide.', 400);
    if (empty($meds))            sendError('Au moins un médicament est requis.', 400);

    /* Validation de la date d'expiration si fournie */
    if ($date_exp && !isDate($date_exp)) sendError('Format date expiration invalide.', 400);

    /* ── Génération du numéro d'ordonnance ── */
    $pdo = getDB();
    /* Récupère le dernier numéro pour incrémenter */
    $lastNum = $pdo->query("SELECT MAX(id) FROM ordonnances")->fetchColumn();
    $numero  = 'ORD-' . date('Y') . '-' . str_pad((int)$lastNum + 1, 3, '0', STR_PAD_LEFT);

    /* ── Transaction : insertion ordonnance + médicaments ── */
    /* Une transaction garantit l'atomicité : tout réussit ou tout échoue */
    $pdo->beginTransaction();

    try {
        /* Insertion de l'ordonnance */
        $sqlOrd = "
            INSERT INTO ordonnances
                (numero, patient_id, medecin_id, diagnostic, date_create, date_expiration,
                 instructions, statut, envoye, created_at)
            VALUES
                (:numero, :patient_id, :medecin_id, :diagnostic, :date_create,
                 :date_exp, :instructions, :statut, :envoye, NOW())
        ";
        $stmtOrd = $pdo->prepare($sqlOrd);
        $stmtOrd->execute([
            ':numero'       => $numero,
            ':patient_id'   => $patient_id,
            ':medecin_id'   => $medecin_id,
            ':diagnostic'   => $diagnostic,
            ':date_create'  => $date_create,
            ':date_exp'     => $date_exp ?: null,         /* NULL si vide */
            ':instructions' => $instructions,
            ':statut'       => $statut,
            ':envoye'       => $envoye
        ]);

        $ordId = (int)$pdo->lastInsertId();   /* ID auto-incrémenté de l'ordonnance */

        /* Insertion des médicaments associés */
        insertMedicaments($pdo, $ordId, $meds);

        /* Valide la transaction : les deux insertions sont confirmées */
        $pdo->commit();

        /* Si envoi demandé : envoie l'email (simulation) */
        if ($envoye) {
            /* envoyerEmailOrdonnance($ordId); */ /* Fonction d'envoi email */
        }

        sendSuccess(['id' => $ordId, 'numero' => $numero, 'message' => 'Ordonnance créée.'], 201);

    } catch (PDOException $e) {
        $pdo->rollBack();   /* Annule toutes les insertions en cas d'erreur */
        sendError('Erreur lors de la création : ' . $e->getMessage(), 500);
    }
}

/* ─────────────────────────────────────────────────────
   10. ACTION : MODIFIER UNE ORDONNANCE
───────────────────────────────────────────────────── */

/**
 * Met à jour une ordonnance existante et ses médicaments
 */
function actionUpdate(): void
{
    /* Lecture du corps JSON */
    $body = json_decode(file_get_contents('php://input'), true);
    if (!is_array($body)) sendError('Corps JSON invalide.', 400);

    /* Extraction des données */
    $id          = (int)($body['id']           ?? 0);
    $diagnostic  = clean($body['diagnostic']   ?? '');
    $date_create = clean($body['date']         ?? '');
    $date_exp    = clean($body['expiration']   ?? '');
    $instructions= clean($body['instructions'] ?? '');
    $statut      = clean($body['statut']       ?? '');
    $envoye      = !empty($body['envoye']) ? 1 : 0;
    $meds        = $body['medicaments'] ?? [];

    /* Validations */
    if ($id <= 0)                sendError('ID ordonnance invalide.', 400);
    if (!isDate($date_create))   sendError('Date invalide.', 400);
    if (!isValidStatut($statut)) sendError('Statut invalide.', 400);
    if (empty($meds))            sendError('Médicaments requis.', 400);

    $pdo = getDB();

    /* Vérifie que l'ordonnance existe */
    $exists = $pdo->prepare("SELECT id FROM ordonnances WHERE id = :id");
    $exists->execute([':id' => $id]);
    if (!$exists->fetch()) sendError('Ordonnance introuvable.', 404);

    /* Transaction pour mettre à jour ordonnance + médicaments */
    $pdo->beginTransaction();

    try {
        /* Mise à jour des champs de l'ordonnance */
        $sqlUpd = "
            UPDATE ordonnances SET
                diagnostic      = :diagnostic,
                date_create     = :date_create,
                date_expiration = :date_exp,
                instructions    = :instructions,
                statut          = :statut,
                envoye          = :envoye,
                updated_at      = NOW()                -- Horodatage modification
            WHERE id = :id
        ";
        $stmtUpd = $pdo->prepare($sqlUpd);
        $stmtUpd->execute([
            ':diagnostic'  => $diagnostic,
            ':date_create' => $date_create,
            ':date_exp'    => $date_exp ?: null,
            ':instructions'=> $instructions,
            ':statut'      => $statut,
            ':envoye'      => $envoye,
            ':id'          => $id
        ]);

        /* Supprime les anciens médicaments */
        $pdo->prepare("DELETE FROM ordonnance_medicaments WHERE ordonnance_id = :id")
            ->execute([':id' => $id]);

        /* Réinsère les nouveaux médicaments */
        insertMedicaments($pdo, $id, $meds);

        $pdo->commit();   /* Valide la transaction */

        sendSuccess(['message' => 'Ordonnance mise à jour avec succès.']);

    } catch (PDOException $e) {
        $pdo->rollBack();
        sendError('Erreur mise à jour : ' . $e->getMessage(), 500);
    }
}

/* ─────────────────────────────────────────────────────
   11. ACTION : SUPPRIMER UNE ORDONNANCE
───────────────────────────────────────────────────── */

/**
 * Supprime une ordonnance et ses médicaments associés
 * La suppression en cascade est gérée par la contrainte FK
 */
function actionDelete(): void
{
    /* Lecture du corps JSON */
    $body = json_decode(file_get_contents('php://input'), true);
    $id   = (int)($body['id'] ?? 0);

    if ($id <= 0) sendError('ID invalide.', 400);

    $pdo = getDB();

    /* Vérifie que l'ordonnance existe avant suppression */
    $check = $pdo->prepare("SELECT numero FROM ordonnances WHERE id = :id");
    $check->execute([':id' => $id]);
    $ord = $check->fetch();
    if (!$ord) sendError('Ordonnance introuvable.', 404);

    /* Suppression (les médicaments sont supprimés en cascade via FK) */
    $stmt = $pdo->prepare("DELETE FROM ordonnances WHERE id = :id");
    $stmt->execute([':id' => $id]);

    sendSuccess(['message' => "Ordonnance {$ord['numero']} supprimée avec succès."]);
}

/* ─────────────────────────────────────────────────────
   12. ACTION : ENVOYER PAR EMAIL
───────────────────────────────────────────────────── */

/**
 * Marque une ordonnance comme envoyée et déclenche l'envoi email
 * (L'envoi réel nécessite une lib comme PHPMailer ou Mailgun)
 */
function actionSendEmail(): void
{
    $body = json_decode(file_get_contents('php://input'), true);
    $id   = (int)($body['id'] ?? 0);

    if ($id <= 0) sendError('ID invalide.', 400);

    $pdo = getDB();

    /* Récupère l'ordonnance avec l'email du patient */
    $sql = "
        SELECT o.numero, o.statut, p.email, p.prenom, p.nom
        FROM ordonnances o
        INNER JOIN patients p ON p.id = o.patient_id
        WHERE o.id = :id
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch();

    if (!$data)           sendError('Ordonnance introuvable.', 404);
    if (!$data['email'])  sendError("Ce patient n'a pas d'adresse email enregistrée.", 422);

    /* Marque comme envoyée en base */
    $pdo->prepare("UPDATE ordonnances SET envoye = 1, updated_at = NOW() WHERE id = :id")
        ->execute([':id' => $id]);

    /*
     * Envoi réel de l'email (exemple avec la fonction mail() native PHP)
     * En production : utiliser PHPMailer, SwiftMailer ou une API (SendGrid, Mailgun)
     *
     * $sujet = "Votre ordonnance " . $data['numero'];
     * $corps = "Bonjour " . $data['prenom'] . ",\n\nVeuillez trouver ci-joint votre ordonnance.";
     * $headers = "From: cabinet@medicare.fr\r\nContent-Type: text/plain; charset=UTF-8";
     * mail($data['email'], $sujet, $corps, $headers);
     */

    sendSuccess([
        'message' => "Ordonnance {$data['numero']} envoyée à {$data['email']}.",
        'numero'  => $data['numero'],
        'email'   => $data['email']
    ]);
}

/* ─────────────────────────────────────────────────────
   13. ACTION : CHANGER LE STATUT
───────────────────────────────────────────────────── */

/**
 * Met à jour uniquement le statut d'une ordonnance
 * (Utile pour les actions rapides depuis le tableau)
 */
function actionChangeStatut(): void
{
    $body   = json_decode(file_get_contents('php://input'), true);
    $id     = (int)($body['id']     ?? 0);
    $statut = clean($body['statut'] ?? '');

    if ($id <= 0)                sendError('ID invalide.', 400);
    if (!isValidStatut($statut)) sendError('Statut invalide.', 400);

    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE ordonnances SET statut = :statut, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':statut' => $statut, ':id' => $id]);

    /* Vérifie qu'une ligne a été affectée */
    if ($stmt->rowCount() === 0) sendError('Ordonnance introuvable.', 404);

    sendSuccess(['message' => "Statut mis à jour : $statut."]);
}

/* ─────────────────────────────────────────────────────
   14. ACTION : GÉNÉRATION PDF
───────────────────────────────────────────────────── */

/**
 * Génère le PDF d'une ordonnance
 * Nécessite la librairie TCPDF ou DOMPDF installée via Composer
 *
 * @param int $id ID de l'ordonnance
 */
function actionGeneratePDF(int $id): void
{
    if ($id <= 0) sendError('ID invalide.', 400);

    /*
     * Exemple avec TCPDF (à installer via Composer : composer require tecnickcom/tcpdf)
     *
     * require_once __DIR__ . '/vendor/autoload.php';
     * $pdo = getDB();
     * // ... récupération des données ...
     * $pdf = new TCPDF('P', 'mm', 'A4');
     * $pdf->AddPage();
     * $pdf->writeHTML($htmlContent);
     * $pdf->Output("ordonnance_$numero.pdf", 'D'); // 'D' = téléchargement forcé
     * exit;
     */

    /* Pour l'instant : retourne une réponse JSON informative */
    sendSuccess([
        'message' => "Génération PDF pour l'ordonnance ID $id.",
        'info'    => "Installez TCPDF ou DOMPDF via Composer pour activer cette fonctionnalité."
    ]);
}

/* ─────────────────────────────────────────────────────
   15. HELPERS INTERNES
───────────────────────────────────────────────────── */

/**
 * Récupère les médicaments d'une ordonnance par son ID
 *
 * @param PDO $pdo   Instance de connexion
 * @param int $ordId ID de l'ordonnance
 * @return array     Tableau de médicaments
 */
function getMedsByOrdId(PDO $pdo, int $ordId): array
{
    $stmt = $pdo->prepare("
        SELECT nom_medicament, dosage, frequence, duree
        FROM ordonnance_medicaments
        WHERE ordonnance_id = :id
        ORDER BY position ASC                    -- Ordre d'affichage
    ");
    $stmt->execute([':id' => $ordId]);

    /* Formate les médicaments pour le frontend */
    return array_map(fn($m) => [
        'nom'   => $m['nom_medicament'],   /* Nom du médicament */
        'dose'  => $m['dosage'],           /* Dosage */
        'freq'  => $m['frequence'],        /* Fréquence de prise */
        'duree' => $m['duree']             /* Durée du traitement */
    ], $stmt->fetchAll());
}

/**
 * Insère les médicaments d'une ordonnance en base
 * Utilisé lors de la création ET de la mise à jour
 *
 * @param PDO   $pdo   Instance de connexion (transaction en cours)
 * @param int   $ordId ID de l'ordonnance parente
 * @param array $meds  Tableau des médicaments à insérer
 */
function insertMedicaments(PDO $pdo, int $ordId, array $meds): void
{
    /* Requête d'insertion d'un médicament */
    $sql = "
        INSERT INTO ordonnance_medicaments
            (ordonnance_id, nom_medicament, dosage, frequence, duree, position)
        VALUES
            (:ordId, :nom, :dose, :freq, :duree, :pos)
    ";
    $stmt = $pdo->prepare($sql);

    /* Insère chaque médicament avec sa position */
    foreach ($meds as $i => $m) {
        $nom   = clean($m['nom']   ?? '');   /* Nom médicament */
        $dose  = clean($m['dose']  ?? '');   /* Dosage */
        $freq  = clean($m['freq']  ?? '');   /* Fréquence */
        $duree = clean($m['duree'] ?? '');   /* Durée */

        if (empty($nom)) continue;           /* Ignore les lignes vides */

        $stmt->execute([
            ':ordId' => $ordId,
            ':nom'   => $nom,
            ':dose'  => $dose ?: '—',        /* Tiret si vide */
            ':freq'  => $freq ?: '—',
            ':duree' => $duree ?: '—',
            ':pos'   => $i + 1               /* Position 1-based */
        ]);
    }
}

/**
 * Formate une ligne SQL d'ordonnance en objet propre pour le frontend
 *
 * @param array $row      Ligne SQL brute
 * @param array $meds     Médicaments associés
 * @param bool  $full     Si true : inclut les infos de contact patient
 * @return array          Objet formaté
 */
function formatOrdonnance(array $row, array $meds, bool $full = false): array
{
    /* Construction de l'objet patient */
    $patient = [
        'id'     => (int)$row['patient_id'],
        'nom'    => $row['patient_prenom'] . ' ' . $row['patient_nom'],
        'age'    => (int)$row['patient_age'],
        'avatar' => $row['patient_photo']
            ? '/uploads/patients/' . $row['patient_photo']
            : 'https://ui-avatars.com/api/?name=' . urlencode($row['patient_prenom'] . '+' . $row['patient_nom']) . '&background=4A7FA7&color=fff&size=60'
    ];

    /* Infos de contact en mode détail */
    if ($full) {
        $patient['email'] = $row['patient_email'] ?? null;
        $patient['tel']   = $row['patient_tel']   ?? null;
    }

    /* Objet ordonnance final */
    return [
        'id'           => (int)$row['id'],
        'numero'       => $row['numero'],
        'patient'      => $patient,
        'diagnostic'   => $row['diagnostic'],
        'medicaments'  => $meds,
        'date'         => $row['date_create'],
        'expiration'   => $row['date_expiration'],
        'instructions' => $row['instructions'],
        'statut'       => $row['statut'],
        'envoye'       => (bool)$row['envoye'],   /* Converti en booléen */
        'created_at'   => $row['created_at'] ?? null
    ];
}

/* ─────────────────────────────────────────────────────
   16. SCRIPT SQL — CRÉATION DES TABLES
   (À exécuter une seule fois pour initialiser la BDD)
───────────────────────────────────────────────────── */

/*
-- Table principale des ordonnances
CREATE TABLE IF NOT EXISTS ordonnances (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    numero           VARCHAR(30)   NOT NULL UNIQUE,           -- N° formaté ORD-XXXX-NNN
    patient_id       INT           NOT NULL,                  -- Référence table patients
    medecin_id       INT           NOT NULL,                  -- Référence table medecins
    diagnostic       VARCHAR(255)  NULL,                      -- Motif de consultation
    date_create      DATE          NOT NULL,                  -- Date de l'ordonnance
    date_expiration  DATE          NULL,                      -- Date d'expiration (optionnel)
    instructions     TEXT          NULL,                      -- Instructions au patient
    statut           ENUM('active','expiree','renouvelee','annulee') NOT NULL DEFAULT 'active',
    envoye           TINYINT(1)    NOT NULL DEFAULT 0,        -- 0=non envoyé, 1=envoyé
    created_at       DATETIME      NOT NULL DEFAULT NOW(),
    updated_at       DATETIME      NULL ON UPDATE NOW(),

    -- Index pour accélérer les requêtes fréquentes
    INDEX idx_patient  (patient_id),
    INDEX idx_medecin  (medecin_id),
    INDEX idx_statut   (statut),
    INDEX idx_date     (date_create),
    INDEX idx_numero   (numero),

    -- Contraintes de clé étrangère
    CONSTRAINT fk_ord_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    CONSTRAINT fk_ord_medecin FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des médicaments liés aux ordonnances
CREATE TABLE IF NOT EXISTS ordonnance_medicaments (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    ordonnance_id   INT           NOT NULL,                   -- Référence ordonnance
    nom_medicament  VARCHAR(150)  NOT NULL,                   -- Nom du médicament
    dosage          VARCHAR(50)   NOT NULL DEFAULT '—',       -- Ex: 500mg, 1g
    frequence       VARCHAR(50)   NOT NULL DEFAULT '—',       -- Ex: 2×/jour, Le matin
    duree           VARCHAR(50)   NOT NULL DEFAULT '—',       -- Ex: 7 jours
    position        INT           NOT NULL DEFAULT 1,         -- Ordre d'affichage

    INDEX idx_med_ordonnance (ordonnance_id),

    -- Suppression en cascade : si l'ordonnance est supprimée, les médicaments aussi
    CONSTRAINT fk_med_ord FOREIGN KEY (ordonnance_id) REFERENCES ordonnances(id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/
?>