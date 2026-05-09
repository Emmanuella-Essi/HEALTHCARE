<?php
/* ============================================================
   DASHBOARD MÉDECIN — PHP Backend
   Fichier     : dashboard-medecin.php
   Description : Contrôleur PHP du tableau de bord médecin
                 Gère la session, la sécurité et les données
   ============================================================ */

// --- Démarrer la session PHP (obligatoire pour vérifier la connexion) ---
session_start();


/* ============================================================
   1. SÉCURITÉ — Vérification de la session médecin
   Cette section est la PREMIÈRE chose à exécuter
   ============================================================ */

/**
 * Vérifie que l'utilisateur est bien connecté ET est un médecin
 * Si non → redirection vers la page de connexion
 */
function verifierSessionMedecin() {
    // Vérifier que les données de session existent
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        // Pas de session → redirige vers login
        header("Location: ../login.php");
        exit(); // IMPORTANT : arrêter l'exécution du script
    }

    // Vérifier que le rôle est bien "medecin" (pas patient, pas admin)
    if ($_SESSION['role'] !== 'medecin') {
        // Rôle incorrect → accès interdit
        header("Location: ../acces-interdit.php");
        exit();
    }

    // Vérifier que la session n'a pas expiré (durée max : 2 heures)
    $duree_max_session = 7200; // 2 heures en secondes
    if (isset($_SESSION['derniere_activite'])) {
        $inactif_depuis = time() - $_SESSION['derniere_activite'];
        if ($inactif_depuis > $duree_max_session) {
            // Session expirée → détruire et rediriger
            session_destroy();
            header("Location: ../login.php?expire=1");
            exit();
        }
    }

    // Mettre à jour le timestamp de dernière activité
    $_SESSION['derniere_activite'] = time();
}

// Appeler la vérification IMMÉDIATEMENT
verifierSessionMedecin();


/* ============================================================
   2. CONNEXION BASE DE DONNÉES
   ============================================================ */

/**
 * Inclure le fichier de configuration de la BDD
 * Ce fichier contient les constantes DB_HOST, DB_NAME, DB_USER, DB_PASS
 */
// require_once 'config/db.php';

/**
 * SIMULATION : Connexion PDO à la base de données MySQL
 * En production, décommenter le code ci-dessous
 */
/*
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Erreurs visibles
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Résultats en tableau associatif
            PDO::ATTR_EMULATE_PREPARES   => false,                   // Requêtes préparées natives
        ]
    );
} catch (PDOException $e) {
    // En cas d'erreur BDD → log discret + message générique
    error_log("Erreur BDD : " . $e->getMessage());
    die("Service temporairement indisponible. Veuillez réessayer.");
}
*/


/* ============================================================
   3. RÉCUPÉRATION DES DONNÉES DU MÉDECIN CONNECTÉ
   ============================================================ */

/**
 * Récupère les informations du médecin depuis la BDD
 * @param PDO $pdo     - Connexion à la base de données
 * @param int $user_id - ID du médecin connecté (depuis la session)
 * @return array       - Tableau avec les infos du médecin
 */
function getMedecinInfo($pdo, $user_id) {
    // Requête préparée (protection contre injection SQL)
    $stmt = $pdo->prepare("
        SELECT
            u.id,
            u.nom,
            u.prenom,
            u.email,
            m.specialite,
            m.telephone,
            m.photo
        FROM utilisateurs u
        INNER JOIN medecins m ON u.id = m.user_id
        WHERE u.id = :user_id
        AND u.actif = 1
        LIMIT 1
    ");

    // Lier le paramètre (évite l'injection SQL)
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(); // Retourne un tableau associatif ou false
}


/* ============================================================
   4. STATISTIQUES DU TABLEAU DE BORD
   ============================================================ */

/**
 * Récupère le nombre total de patients suivis par ce médecin
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return int            - Nombre de patients
 */
function getNbPatients($pdo, $medecin_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM patients p
        WHERE p.medecin_id = :medecin_id
        AND p.actif = 1
    ");
    $stmt->bindParam(':medecin_id', $medecin_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
    return (int) $result['total'];
}

/**
 * Récupère le nombre de consultations du jour
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return array          - ['total' => nb, 'en_attente' => nb]
 */
function getConsultationsJour($pdo, $medecin_id) {
    $aujourd_hui = date('Y-m-d'); // Format date MySQL

    $stmt = $pdo->prepare("
        SELECT
            COUNT(*) as total,
            SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) as en_attente
        FROM consultations
        WHERE medecin_id = :medecin_id
        AND DATE(date_consultation) = :aujourd_hui
    ");
    $stmt->bindParam(':medecin_id',  $medecin_id,   PDO::PARAM_INT);
    $stmt->bindParam(':aujourd_hui', $aujourd_hui,  PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch();
}

/**
 * Récupère le nombre de messages non lus pour ce médecin
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return int            - Nombre de messages non lus
 */
function getMessagesNonLus($pdo, $medecin_id) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM messages
        WHERE destinataire_id = :medecin_id
        AND lu = 0
    ");
    $stmt->bindParam(':medecin_id', $medecin_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
    return (int) $result['total'];
}

/**
 * Récupère le nombre de vaccins en retard pour tous les patients du médecin
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return int            - Nombre de vaccins en retard
 */
function getVaccinsEnRetard($pdo, $medecin_id) {
    $aujourd_hui = date('Y-m-d');

    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM vaccins v
        INNER JOIN patients p ON v.patient_id = p.id
        WHERE p.medecin_id = :medecin_id
        AND v.date_rappel < :aujourd_hui      -- Date de rappel dépassée
        AND v.statut = 'a_faire'              -- Pas encore effectué
    ");
    $stmt->bindParam(':medecin_id',  $medecin_id,  PDO::PARAM_INT);
    $stmt->bindParam(':aujourd_hui', $aujourd_hui, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    return (int) $result['total'];
}

/**
 * Récupère la liste des 5 patients récents du médecin
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return array          - Tableau des patients
 */
function getPatientsRecents($pdo, $medecin_id) {
    $stmt = $pdo->prepare("
        SELECT
            p.id,
            p.nom,
            p.prenom,
            p.age,
            p.groupe_sanguin,
            p.statut_sante,
            MAX(c.date_consultation) as derniere_visite
        FROM patients p
        LEFT JOIN consultations c ON p.id = c.patient_id
        WHERE p.medecin_id = :medecin_id
        AND p.actif = 1
        GROUP BY p.id
        ORDER BY derniere_visite DESC     -- Trier par visite la plus récente
        LIMIT 5
    ");
    $stmt->bindParam(':medecin_id', $medecin_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(); // Retourne un tableau de tableaux associatifs
}

/**
 * Récupère les consultations en attente de confirmation
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return array          - Liste des consultations en attente
 */
function getConsultationsEnAttente($pdo, $medecin_id) {
    $stmt = $pdo->prepare("
        SELECT
            c.id,
            c.motif,
            c.heure_souhaitee,
            c.statut,
            c.date_demande,
            p.nom    as patient_nom,
            p.prenom as patient_prenom
        FROM consultations c
        INNER JOIN patients p ON c.patient_id = p.id
        WHERE c.medecin_id = :medecin_id
        AND c.statut = 'en_attente'
        ORDER BY c.date_demande ASC       -- Les plus anciennes en premier
        LIMIT 10
    ");
    $stmt->bindParam(':medecin_id', $medecin_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupère les derniers messages patients non lus
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return array          - 5 derniers messages
 */
function getDerniersMessages($pdo, $medecin_id) {
    $stmt = $pdo->prepare("
        SELECT
            m.id,
            m.contenu,
            m.date_envoi,
            m.lu,
            p.nom    as expediteur_nom,
            p.prenom as expediteur_prenom,
            COUNT(m2.id) as nb_non_lus     -- Compter les non lus dans la conversation
        FROM messages m
        INNER JOIN patients p ON m.expediteur_id = p.id
        LEFT JOIN messages m2
            ON m2.conversation_id = m.conversation_id
            AND m2.lu = 0
        WHERE m.destinataire_id = :medecin_id
        GROUP BY m.conversation_id
        ORDER BY m.date_envoi DESC
        LIMIT 5
    ");
    $stmt->bindParam(':medecin_id', $medecin_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupère les vaccins en retard avec détails patients
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return array          - Liste des vaccins en retard
 */
function getVaccinsRetardDetail($pdo, $medecin_id) {
    $aujourd_hui = date('Y-m-d');

    $stmt = $pdo->prepare("
        SELECT
            v.id,
            v.nom_vaccin,
            v.date_rappel,
            DATEDIFF(:aujourd_hui, v.date_rappel) as jours_retard,
            p.nom    as patient_nom,
            p.prenom as patient_prenom
        FROM vaccins v
        INNER JOIN patients p ON v.patient_id = p.id
        WHERE p.medecin_id = :medecin_id
        AND v.date_rappel < :aujourd_hui2
        AND v.statut = 'a_faire'
        ORDER BY v.date_rappel ASC        -- Les plus urgents en premier
        LIMIT 5
    ");
    $stmt->bindParam(':medecin_id',   $medecin_id,  PDO::PARAM_INT);
    $stmt->bindParam(':aujourd_hui',  $aujourd_hui, PDO::PARAM_STR);
    $stmt->bindParam(':aujourd_hui2', $aujourd_hui, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupère les données d'activité des 7 derniers jours (pour le graphique)
 * @param PDO $pdo        - Connexion BDD
 * @param int $medecin_id - ID du médecin
 * @return array          - Données par jour [date, nb_consultations, nb_nouveaux]
 */
function getActivite7Jours($pdo, $medecin_id) {
    $stmt = $pdo->prepare("
        SELECT
            DATE(c.date_consultation) as jour,
            COUNT(c.id)               as nb_consultations,
            SUM(CASE WHEN p.date_inscription >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as nb_nouveaux
        FROM consultations c
        INNER JOIN patients p ON c.patient_id = p.id
        WHERE c.medecin_id = :medecin_id
        AND c.date_consultation >= DATE_SUB(NOW(), INTERVAL 7 DAY)  -- 7 derniers jours
        GROUP BY DATE(c.date_consultation)
        ORDER BY jour ASC
    ");
    $stmt->bindParam(':medecin_id', $medecin_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}


/* ============================================================
   5. ACTIONS AJAX — Traitement des requêtes POST
   (Accepter/refuser une consultation, marquer message lu, etc.)
   ============================================================ */

/**
 * Traite les requêtes AJAX envoyées depuis dashboard-medecin.js
 * header("Content-Type: application/json") → répond en JSON
 */
if (isset($_POST['action'])) {
    // Toutes les réponses AJAX sont en JSON
    header("Content-Type: application/json; charset=UTF-8");

    $action     = $_POST['action'];
    $medecin_id = (int) $_SESSION['user_id'];

    switch ($action) {

        /**
         * Accepter une consultation
         * POST : action=accepter_consultation, consultation_id=X
         */
        case 'accepter_consultation':
            $consultation_id = filter_input(INPUT_POST, 'consultation_id', FILTER_VALIDATE_INT);

            if (!$consultation_id) {
                echo json_encode(['succes' => false, 'message' => 'ID invalide']);
                exit();
            }

            // Mettre à jour le statut dans la BDD
            /*
            $stmt = $pdo->prepare("
                UPDATE consultations
                SET statut = 'acceptee', date_modification = NOW()
                WHERE id = :id
                AND medecin_id = :medecin_id  -- Sécurité : vérifier que c'est bien son patient
            ");
            $stmt->bindParam(':id',          $consultation_id, PDO::PARAM_INT);
            $stmt->bindParam(':medecin_id',  $medecin_id,      PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['succes' => true, 'message' => 'Consultation acceptée']);
            */

            // SIMULATION (sans BDD)
            echo json_encode(['succes' => true, 'message' => 'Consultation acceptée']);
            break;

        /**
         * Refuser une consultation
         * POST : action=refuser_consultation, consultation_id=X
         */
        case 'refuser_consultation':
            $consultation_id = filter_input(INPUT_POST, 'consultation_id', FILTER_VALIDATE_INT);

            if (!$consultation_id) {
                echo json_encode(['succes' => false, 'message' => 'ID invalide']);
                exit();
            }

            // SIMULATION
            echo json_encode(['succes' => true, 'message' => 'Consultation refusée']);
            break;

        /**
         * Marquer un message comme lu
         * POST : action=marquer_lu, message_id=X
         */
        case 'marquer_lu':
            $message_id = filter_input(INPUT_POST, 'message_id', FILTER_VALIDATE_INT);

            // SIMULATION
            echo json_encode(['succes' => true, 'message' => 'Message marqué comme lu']);
            break;

        // Action inconnue
        default:
            echo json_encode(['succes' => false, 'message' => 'Action non reconnue']);
            break;
    }

    exit(); // Arrêter après une réponse AJAX
}


/* ============================================================
   6. CHARGEMENT DES DONNÉES POUR L'AFFICHAGE
   (Exécuté lors d'un chargement normal de la page)
   ============================================================ */

/**
 * Récupère toutes les données nécessaires au dashboard
 * En production : décommenter les appels réels à la BDD
 */
$medecin_id = (int) $_SESSION['user_id'];

// --- Données de démonstration (SIMULATION sans BDD) ---
// En production, remplacer par les appels fonctions ci-dessus

$medecin = [
    'nom'       => 'Kouamé',
    'prenom'    => 'Didier',
    'specialite'=> 'Médecin Généraliste',
    'email'     => 'dr.kouame@healthcare.ci'
];

$stats = [
    'nb_patients'         => 48,
    'consultations_jour'  => 6,
    'en_attente'          => 3,
    'messages_non_lus'    => 7,
    'vaccins_en_retard'   => 4
];

/*
// --- Production : décommenter ces lignes ---
$medecin          = getMedecinInfo($pdo, $medecin_id);
$nb_patients      = getNbPatients($pdo, $medecin_id);
$consults_jour    = getConsultationsJour($pdo, $medecin_id);
$messages_non_lus = getMessagesNonLus($pdo, $medecin_id);
$vaccins_retard   = getVaccinsEnRetard($pdo, $medecin_id);
$patients_recents = getPatientsRecents($pdo, $medecin_id);
$consultations    = getConsultationsEnAttente($pdo, $medecin_id);
$messages         = getDerniersMessages($pdo, $medecin_id);
$activite_7j      = getActivite7Jours($pdo, $medecin_id);
*/


/* ============================================================
   7. SÉCURITÉ — Fonction d'échappement HTML
   ============================================================ */

/**
 * Échappe les caractères spéciaux HTML pour prévenir le XSS
 * À utiliser TOUJOURS avant d'afficher une donnée venant de la BDD
 *
 * @param string $valeur - Valeur à sécuriser
 * @return string        - Valeur sécurisée pour affichage HTML
 */
function e($valeur) {
    return htmlspecialchars($valeur, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$html_path = __DIR__ . '/dashboard-medecin.html';

if (!is_file($html_path)) {
    http_response_code(404);
    echo 'Interface dashboard introuvable.';
    exit();
}

$stats_json = json_encode($stats, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
$medecin_json = json_encode([
    'nom'        => $medecin['nom'],
    'prenom'     => $medecin['prenom'],
    'specialite' => $medecin['specialite'],
    'email'      => $medecin['email'],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

$injection = <<<HTML
  <script>
    window.PHP_STATS = {$stats_json};
    window.PHP_MEDECIN = {$medecin_json};
  </script>
HTML;

$html = file_get_contents($html_path);
$html = str_replace('</head>', $injection . PHP_EOL . '</head>', $html);

echo $html;
exit();
