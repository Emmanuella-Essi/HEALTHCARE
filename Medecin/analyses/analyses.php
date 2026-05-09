<?php
/* ============================================================
   analyses.php — Backend API : Données Analytiques
   Fournit les données JSON pour les graphiques du frontend
   Toutes les requêtes nécessitent une session médecin active
   ============================================================ */

/* ---- En-têtes HTTP : JSON + CORS ---- */
header('Content-Type: application/json; charset=UTF-8'); /* Réponse JSON */
header('Access-Control-Allow-Origin: *');                /* Autorise requêtes AJAX */
header('Access-Control-Allow-Methods: GET');             /* API lecture seule */
header('Access-Control-Allow-Headers: Content-Type');

/* ---- Session ---- */
session_start(); /* Démarre la session pour lire $_SESSION */

/* ============================================================
   VÉRIFICATION AUTHENTIFICATION
   ============================================================ */

/**
 * Vérifie que l'utilisateur connecté est un médecin
 * Coupe l'exécution avec erreur 401 si non authentifié
 */
function verifierAuthentification() {
    if (!isset($_SESSION['medecin_id'])) {
        http_response_code(401); /* Non autorisé */
        echo json_encode(['success' => false, 'message' => 'Non connecté.']);
        exit;
    }
}

/* ============================================================
   CONNEXION BASE DE DONNÉES
   ============================================================ */

/**
 * Retourne une instance PDO connectée à MySQL
 * @return PDO
 */
function getDB() {
    static $pdo = null; /* Instance statique = connexion unique par requête */

    if ($pdo !== null) return $pdo; /* Réutilise la connexion si déjà établie */

    $host    = 'localhost';
    $dbname  = 'medicare_db';
    $user    = 'root';
    $pass    = '';
    $charset = 'utf8mb4';

    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$dbname};charset={$charset}",
            $user, $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, /* Exceptions sur erreur */
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       /* Tableaux associatifs */
                PDO::ATTR_EMULATE_PREPARES   => false,                  /* Requêtes natives */
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur BDD.']);
        exit;
    }
}

/* ============================================================
   UTILITAIRES
   ============================================================ */

/**
 * Retourne une réponse JSON de succès et arrête le script
 * @param mixed  $data    - Données à retourner
 * @param string $message - Message de succès
 */
function ok($data, $message = 'OK') {
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data]);
    exit;
}

/**
 * Retourne une réponse JSON d'erreur et arrête le script
 * @param string $message - Description de l'erreur
 * @param int    $code    - Code HTTP (400, 404, 500...)
 */
function erreur($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

/**
 * Nettoie une valeur entrante (protection XSS basique)
 * @param string $v - Valeur brute
 * @return string   - Valeur nettoyée
 */
function clean($v) {
    return htmlspecialchars(strip_tags(trim((string)$v)));
}

/* ============================================================
   ROUTAGE
   ============================================================ */

verifierAuthentification(); /* Vérification auth obligatoire */

$action    = isset($_GET['action'])  ? clean($_GET['action'])  : ''; /* Action demandée */
$medecinId = (int)$_SESSION['medecin_id'];                           /* ID médecin session */

/* Nombre de mois pour les requêtes temporelles (6, 12 ou 24) */
$mois = isset($_GET['mois']) ? max(1, min(24, (int)$_GET['mois'])) : 6;

/* Routage selon l'action */
switch ($action) {
    case 'patients_par_mois':    getPatientParMois($medecinId, $mois); break;
    case 'pathologies':          getPathologies($medecinId);           break;
    case 'vaccins':              getVaccins($medecinId, $mois);        break;
    case 'messagerie':           getMessagerie($medecinId, $mois);     break;
    case 'kpi':                  getKPI($medecinId);                   break;
    case 'recap_mensuel':        getRecapMensuel($medecinId, $mois);   break;
    default: erreur('Action inconnue. Actions disponibles : patients_par_mois, pathologies, vaccins, messagerie, kpi, recap_mensuel.');
}


/* ============================================================
   FONCTIONS DE DONNÉES
   ============================================================ */

/* ------------------------------------------------------------ */
/* KPI : Indicateurs clés                                       */
/* ------------------------------------------------------------ */

/**
 * Retourne les 4 valeurs KPI du tableau de bord
 * Total patients, consultations ce mois, vaccins, messages
 *
 * @param int $medecinId - ID médecin connecté
 */
function getKPI($medecinId) {
    $pdo         = getDB();
    $moisActuel  = date('Y-m');   /* Format YYYY-MM */
    $moisPrecedent = date('Y-m', strtotime('-1 month')); /* Mois précédent pour comparaison */

    try {
        /* --- Total patients --- */
        $stmtP = $pdo->prepare("
            SELECT
                COUNT(*) AS total,
                /* Patients ajoutés ce mois */
                SUM(CASE WHEN DATE_FORMAT(created_at,'%Y-%m') = :mois THEN 1 ELSE 0 END) AS ce_mois,
                /* Patients ajoutés le mois précédent */
                SUM(CASE WHEN DATE_FORMAT(created_at,'%Y-%m') = :mois_prec THEN 1 ELSE 0 END) AS mois_prec
            FROM patients
            WHERE medecin_id = :id AND deleted_at IS NULL
        ");
        $stmtP->execute([':id' => $medecinId, ':mois' => $moisActuel, ':mois_prec' => $moisPrecedent]);
        $p = $stmtP->fetch();

        /* --- Consultations ce mois --- */
        $stmtC = $pdo->prepare("
            SELECT
                SUM(CASE WHEN DATE_FORMAT(date_consultation,'%Y-%m') = :mois THEN 1 ELSE 0 END) AS ce_mois,
                SUM(CASE WHEN DATE_FORMAT(date_consultation,'%Y-%m') = :mois_prec THEN 1 ELSE 0 END) AS mois_prec
            FROM consultations
            WHERE medecin_id = :id AND deleted_at IS NULL
        ");
        $stmtC->execute([':id' => $medecinId, ':mois' => $moisActuel, ':mois_prec' => $moisPrecedent]);
        $c = $stmtC->fetch();

        /* --- Vaccins effectués --- */
        $stmtV = $pdo->prepare("
            SELECT
                SUM(CASE WHEN statut = 'effectue' AND DATE_FORMAT(date_vaccination,'%Y-%m') = :mois THEN 1 ELSE 0 END) AS ce_mois,
                SUM(CASE WHEN statut = 'en_retard' THEN 1 ELSE 0 END) AS en_retard
            FROM vaccins v
            INNER JOIN patients pat ON v.patient_id = pat.id
            WHERE pat.medecin_id = :id
        ");
        $stmtV->execute([':id' => $medecinId, ':mois' => $moisActuel]);
        $v = $stmtV->fetch();

        /* --- Messages échangés --- */
        $stmtM = $pdo->prepare("
            SELECT COUNT(*) AS total
            FROM messages
            WHERE (medecin_id = :id OR destinataire_id = :id2)
              AND DATE_FORMAT(created_at,'%Y-%m') = :mois
        ");
        $stmtM->execute([':id' => $medecinId, ':id2' => $medecinId, ':mois' => $moisActuel]);
        $m = $stmtM->fetch();

        /* Calcule les tendances en pourcentage */
        $tendancePatients = $p['mois_prec'] > 0
            ? round((($p['ce_mois'] - $p['mois_prec']) / $p['mois_prec']) * 100, 1)
            : 0;

        $tendanceConsultations = $c['mois_prec'] > 0
            ? round((($c['ce_mois'] - $c['mois_prec']) / $c['mois_prec']) * 100, 1)
            : 0;

        ok([
            'totalPatients'          => (int)($p['total']   ?? 0),
            'patientsEvolution'      => $tendancePatients,      /* % hausse/baisse */
            'consultationsMois'      => (int)($c['ce_mois'] ?? 0),
            'consultationsEvolution' => $tendanceConsultations,
            'vaccinsEffectues'       => (int)($v['ce_mois'] ?? 0),
            'vaccinsEnRetard'        => (int)($v['en_retard'] ?? 0),
            'messagesTotal'          => (int)($m['total']   ?? 0),
        ], 'KPI récupérés');

    } catch (PDOException $e) {
        erreur('Erreur récupération KPI.', 500);
    }
}

/* ------------------------------------------------------------ */
/* PATIENTS PAR MOIS (données courbe)                           */
/* ------------------------------------------------------------ */

/**
 * Retourne le nombre de nouveaux patients et de consultations
 * pour chaque mois sur les N derniers mois
 *
 * @param int $medecinId - ID médecin
 * @param int $mois      - Nombre de mois à retourner
 */
function getPatientParMois($medecinId, $mois) {
    $pdo = getDB();

    try {
        /* Requête : nouveaux patients groupés par mois */
        $stmtP = $pdo->prepare("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS mois_key,        /* Clé YYYY-MM */
                DATE_FORMAT(created_at, '%b %Y') AS mois_label,       /* Label lisible (Jan 2025) */
                COUNT(*) AS nb_patients                               /* Comptage */
            FROM patients
            WHERE medecin_id = :id
              AND deleted_at IS NULL
              AND created_at >= DATE_SUB(NOW(), INTERVAL :mois MONTH) /* Filtre période */
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')                 /* Groupe par mois */
            ORDER BY mois_key ASC                                     /* Ordre chronologique */
        ");
        $stmtP->execute([':id' => $medecinId, ':mois' => $mois]);
        $patients = $stmtP->fetchAll();

        /* Requête : consultations groupées par mois */
        $stmtC = $pdo->prepare("
            SELECT
                DATE_FORMAT(date_consultation, '%Y-%m') AS mois_key,
                COUNT(*) AS nb_consultations
            FROM consultations
            WHERE medecin_id = :id
              AND deleted_at IS NULL
              AND date_consultation >= DATE_SUB(NOW(), INTERVAL :mois MONTH)
            GROUP BY DATE_FORMAT(date_consultation, '%Y-%m')
            ORDER BY mois_key ASC
        ");
        $stmtC->execute([':id' => $medecinId, ':mois' => $mois]);
        $consultations = $stmtC->fetchAll();

        /* Construit un index par clé mois pour la jointure */
        $consIndex = [];
        foreach ($consultations as $c) {
            $consIndex[$c['mois_key']] = (int)$c['nb_consultations'];
        }

        /* Fusionne les données patients + consultations */
        $labels        = [];
        $nbPatients    = [];
        $nbConsultations = [];

        foreach ($patients as $p) {
            $labels[]          = $p['mois_label'];                      /* Label du mois */
            $nbPatients[]      = (int)$p['nb_patients'];
            $nbConsultations[] = $consIndex[$p['mois_key']] ?? 0;      /* 0 si pas de consultations */
        }

        ok([
            'labels'        => $labels,
            'patients'      => $nbPatients,
            'consultations' => $nbConsultations,
        ], 'Données patients/mois récupérées');

    } catch (PDOException $e) {
        erreur('Erreur récupération patients par mois.', 500);
    }
}

/* ------------------------------------------------------------ */
/* PATHOLOGIES (données camembert)                              */
/* ------------------------------------------------------------ */

/**
 * Retourne le top 6 des pathologies/motifs les plus fréquents
 *
 * @param int $medecinId - ID médecin
 */
function getPathologies($medecinId) {
    $pdo = getDB();

    try {
        /* Récupère les motifs de consultation les plus fréquents */
        $stmt = $pdo->prepare("
            SELECT
                motif,          /* Motif de consultation */
                COUNT(*) AS nb  /* Nombre d'occurrences */
            FROM consultations
            WHERE medecin_id = :id
              AND deleted_at IS NULL
              AND statut IN ('confirme', 'termine') /* Consultations réelles seulement */
            GROUP BY motif       /* Groupe par motif exact */
            ORDER BY nb DESC     /* Les plus fréquents en premier */
            LIMIT 6              /* Top 6 pour le camembert */
        ");
        $stmt->execute([':id' => $medecinId]);
        $resultats = $stmt->fetchAll();

        /* Extrait les labels et valeurs */
        $labels  = array_column($resultats, 'motif');
        $valeurs = array_map('intval', array_column($resultats, 'nb'));

        /* Calcule le total pour les pourcentages */
        $total = array_sum($valeurs);

        ok([
            'labels'  => $labels,
            'valeurs' => $valeurs,
            'total'   => $total,
        ], 'Pathologies récupérées');

    } catch (PDOException $e) {
        erreur('Erreur récupération pathologies.', 500);
    }
}

/* ------------------------------------------------------------ */
/* VACCINS (données barres groupées)                            */
/* ------------------------------------------------------------ */

/**
 * Retourne vaccins effectués et en retard par mois
 *
 * @param int $medecinId - ID médecin
 * @param int $mois      - Période en mois
 */
function getVaccins($medecinId, $mois) {
    $pdo = getDB();

    try {
        $stmt = $pdo->prepare("
            SELECT
                DATE_FORMAT(COALESCE(date_vaccination, date_rappel), '%Y-%m') AS mois_key,
                DATE_FORMAT(COALESCE(date_vaccination, date_rappel), '%b %Y') AS mois_label,
                /* Vaccins effectués ce mois */
                SUM(CASE WHEN statut = 'effectue' THEN 1 ELSE 0 END) AS effectues,
                /* Vaccins en retard ce mois */
                SUM(CASE WHEN statut = 'en_retard' THEN 1 ELSE 0 END) AS en_retard
            FROM vaccins v
            INNER JOIN patients p ON v.patient_id = p.id   /* Jointure pour filtrer par médecin */
            WHERE p.medecin_id = :id
              AND COALESCE(date_vaccination, date_rappel) >= DATE_SUB(NOW(), INTERVAL :mois MONTH)
            GROUP BY mois_key
            ORDER BY mois_key ASC
        ");
        $stmt->execute([':id' => $medecinId, ':mois' => $mois]);
        $resultats = $stmt->fetchAll();

        ok([
            'labels'    => array_column($resultats, 'mois_label'),
            'effectues' => array_map('intval', array_column($resultats, 'effectues')),
            'en_retard' => array_map('intval', array_column($resultats, 'en_retard')),
        ], 'Données vaccins récupérées');

    } catch (PDOException $e) {
        erreur('Erreur récupération vaccins.', 500);
    }
}

/* ------------------------------------------------------------ */
/* MESSAGERIE (données courbe double)                           */
/* ------------------------------------------------------------ */

/**
 * Retourne les messages reçus et envoyés par mois
 *
 * @param int $medecinId - ID médecin
 * @param int $mois      - Période en mois
 */
function getMessagerie($medecinId, $mois) {
    $pdo = getDB();

    try {
        $stmt = $pdo->prepare("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS mois_key,
                DATE_FORMAT(created_at, '%b %Y') AS mois_label,
                /* Messages envoyés par le médecin */
                SUM(CASE WHEN expediteur_id = :id  THEN 1 ELSE 0 END) AS envoyes,
                /* Messages reçus par le médecin */
                SUM(CASE WHEN destinataire_id = :id2 THEN 1 ELSE 0 END) AS recus
            FROM messages
            WHERE (expediteur_id = :id3 OR destinataire_id = :id4)
              AND created_at >= DATE_SUB(NOW(), INTERVAL :mois MONTH)
            GROUP BY mois_key
            ORDER BY mois_key ASC
        ");
        $stmt->execute([
            ':id'   => $medecinId,
            ':id2'  => $medecinId,
            ':id3'  => $medecinId,
            ':id4'  => $medecinId,
            ':mois' => $mois
        ]);
        $resultats = $stmt->fetchAll();

        ok([
            'labels'  => array_column($resultats, 'mois_label'),
            'envoyes' => array_map('intval', array_column($resultats, 'envoyes')),
            'recus'   => array_map('intval', array_column($resultats, 'recus')),
        ], 'Données messagerie récupérées');

    } catch (PDOException $e) {
        erreur('Erreur récupération messagerie.', 500);
    }
}

/* ------------------------------------------------------------ */
/* RÉCAPITULATIF MENSUEL (tableau)                              */
/* ------------------------------------------------------------ */

/**
 * Retourne le récapitulatif complet pour le tableau HTML
 * Combine patients, consultations, vaccins et messages par mois
 *
 * @param int $medecinId - ID médecin
 * @param int $mois      - Nombre de mois
 */
function getRecapMensuel($medecinId, $mois) {
    $pdo = getDB();

    /* Génère la liste des N derniers mois */
    $periodes = [];
    for ($i = $mois - 1; $i >= 0; $i--) {
        $periodes[] = date('Y-m', strtotime("-{$i} months")); /* Format YYYY-MM */
    }

    /* Placeholder pour la requête IN */
    $placeholders = implode(',', array_fill(0, count($periodes), '?'));

    try {
        /* Requête unique combinant toutes les métriques via sous-requêtes */
        $stmt = $pdo->prepare("
            SELECT
                p.mois                AS mois_key,
                p.nb_patients         AS patients,
                COALESCE(c.nb, 0)     AS consultations,
                COALESCE(v.effectues, 0) AS vaccins_ok,
                COALESCE(v.retard, 0)    AS vaccins_retard,
                COALESCE(m.nb, 0)     AS messages

            /* Sous-requête patients : compte par mois */
            FROM (
                SELECT DATE_FORMAT(created_at,'%Y-%m') AS mois, COUNT(*) AS nb_patients
                FROM patients
                WHERE medecin_id = ?
                  AND deleted_at IS NULL
                  AND DATE_FORMAT(created_at,'%Y-%m') IN ($placeholders)
                GROUP BY mois
            ) p

            /* Jointure consultations */
            LEFT JOIN (
                SELECT DATE_FORMAT(date_consultation,'%Y-%m') AS mois, COUNT(*) AS nb
                FROM consultations
                WHERE medecin_id = ?
                  AND deleted_at IS NULL
                GROUP BY mois
            ) c ON c.mois = p.mois

            /* Jointure vaccins */
            LEFT JOIN (
                SELECT
                    DATE_FORMAT(COALESCE(date_vaccination, date_rappel),'%Y-%m') AS mois,
                    SUM(statut='effectue') AS effectues,
                    SUM(statut='en_retard') AS retard
                FROM vaccins v2
                INNER JOIN patients pat ON v2.patient_id = pat.id
                WHERE pat.medecin_id = ?
                GROUP BY mois
            ) v ON v.mois = p.mois

            /* Jointure messages */
            LEFT JOIN (
                SELECT DATE_FORMAT(created_at,'%Y-%m') AS mois, COUNT(*) AS nb
                FROM messages
                WHERE expediteur_id = ? OR destinataire_id = ?
                GROUP BY mois
            ) m ON m.mois = p.mois

            ORDER BY p.mois ASC
        ");

        /* Paramètres pour les sous-requêtes (ordonnés comme les ? dans la requête) */
        $params = array_merge(
            [$medecinId],    /* patients.medecin_id */
            $periodes,       /* IN (mois1, mois2...) */
            [$medecinId],    /* consultations.medecin_id */
            [$medecinId],    /* patients.medecin_id dans vaccins */
            [$medecinId],    /* messages.expediteur_id */
            [$medecinId]     /* messages.destinataire_id */
        );
        $stmt->execute($params);
        $lignes = $stmt->fetchAll();

        /* Formate les données pour le frontend */
        $recap = array_map(function($l) {
            /* Taux de complétion vaccins */
            $totalVaccins = $l['vaccins_ok'] + $l['vaccins_retard'];
            $taux = $totalVaccins > 0
                  ? round(($l['vaccins_ok'] / $totalVaccins) * 100)
                  : 0;

            return [
                'mois'           => $l['mois_key'],
                'patients'       => (int)$l['patients'],
                'consultations'  => (int)$l['consultations'],
                'vaccinsOk'      => (int)$l['vaccins_ok'],
                'vaccinsRetard'  => (int)$l['vaccins_retard'],
                'messages'       => (int)$l['messages'],
                'taux'           => $taux, /* % complétion vaccins */
            ];
        }, $lignes);

        ok($recap, 'Récapitulatif mensuel récupéré');

    } catch (PDOException $e) {
        erreur('Erreur récupération récapitulatif.', 500);
    }
}

/* ============================================================
   FIN DU FICHIER analyses.php
   ============================================================ */
?>