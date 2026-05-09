<?php
// ============================================================
// messagerie.php — Backend API de la Messagerie
// Gestion des conversations et messages en base de données
// Retourne du JSON pour être consommé par messagerie.js
// ============================================================

// ---- Activation du rapport d'erreurs (désactiver en production) ----
error_reporting(E_ALL);                   // Affiche toutes les erreurs PHP
ini_set('display_errors', 0);            // Ne les affiche PAS au client (journalise seulement)

// ---- En-têtes HTTP obligatoires pour une API JSON ----
header('Content-Type: application/json; charset=UTF-8'); // Réponse en JSON UTF-8
header('Access-Control-Allow-Origin: *');                // Autorise les requêtes cross-origin (CORS)
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Méthodes HTTP autorisées
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // En-têtes autorisés

// ============================================================
// CONFIGURATION BASE DE DONNÉES
// Centralise les paramètres de connexion MySQL
// ============================================================
define('DB_HOST', 'localhost');     // Hôte MySQL (XAMPP : localhost)
define('DB_NAME', 'medidash_db');   // Nom de la base de données
define('DB_USER', 'root');          // Utilisateur MySQL (XAMPP : root)
define('DB_PASS', '');              // Mot de passe MySQL (XAMPP : vide par défaut)
define('DB_CHARSET', 'utf8mb4');    // Jeu de caractères (supporte les emojis)

// ============================================================
// CONNEXION À LA BASE DE DONNÉES VIA PDO
// PDO permet la protection contre les injections SQL
// ============================================================
function getDbConnection() {
    // Chaîne DSN (Data Source Name) pour PDO
    $dsn = "mysql:host=" . DB_HOST
         . ";dbname=" . DB_NAME
         . ";charset=" . DB_CHARSET;

    // Options PDO pour la sécurité et la performance
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lance des exceptions en cas d'erreur
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Retourne des tableaux associatifs
        PDO::ATTR_EMULATE_PREPARES   => false,                   // Désactive l'émulation (plus sécurisé)
    ];

    try {
        // Crée et retourne la connexion PDO
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // En cas d'échec de connexion, retourne une erreur JSON
        sendError(500, "Erreur de connexion à la base de données : " . $e->getMessage());
        exit; // Arrête l'exécution
    }
}

// ============================================================
// SESSION — Vérifie que le médecin est bien connecté
// Protège toutes les routes de l'API
// ============================================================
function checkAuth() {
    session_start(); // Démarre ou reprend la session PHP

    // Vérifie si la variable de session 'medecin_id' existe
    if (!isset($_SESSION['medecin_id'])) {
        sendError(401, "Non autorisé. Veuillez vous connecter."); // Erreur 401 Unauthorized
        exit; // Arrête l'exécution
    }

    return (int)$_SESSION['medecin_id']; // Retourne l'ID du médecin connecté (entier)
}

// ============================================================
// ROUTAGE — Dirige selon la méthode HTTP et l'action demandée
// L'action est passée en paramètre GET : ?action=xxx
// ============================================================
$method = $_SERVER['REQUEST_METHOD'];   // Méthode HTTP : GET, POST, PUT, DELETE
$action = $_GET['action'] ?? '';        // Action demandée (paramètre URL ?action=)

// Vérifie l'authentification pour toutes les requêtes
$medecinId = checkAuth(); // Récupère l'ID du médecin ou arrête l'exécution

// Connexion à la base de données
$pdo = getDbConnection();

// Routeur principal : aiguille vers la bonne fonction selon l'action
switch ($action) {

    case 'liste_conversations':
        // GET — Récupère toutes les conversations du médecin
        getListeConversations($pdo, $medecinId);
        break;

    case 'messages':
        // GET — Récupère les messages d'une conversation spécifique
        getMessages($pdo, $medecinId);
        break;

    case 'envoyer':
        // POST — Envoie un nouveau message
        envoyerMessage($pdo, $medecinId);
        break;

    case 'marquer_lu':
        // PUT — Marque les messages d'une conversation comme lus
        marquerCommentLu($pdo, $medecinId);
        break;

    case 'nouvelles_notifications':
        // GET — Vérifie s'il y a de nouveaux messages (polling)
        getNouvellesNotifications($pdo, $medecinId);
        break;

    case 'statut_patient':
        // GET — Vérifie le statut en ligne d'un patient
        getStatutPatient($pdo);
        break;

    default:
        // Action inconnue : retourne une erreur 400 Bad Request
        sendError(400, "Action invalide ou manquante.");
        break;
}

// ============================================================
// FONCTION 1 : LISTE DES CONVERSATIONS
// Retourne toutes les conversations avec le dernier message
// ============================================================
function getListeConversations(PDO $pdo, int $medecinId): void {
    // Requête SQL : récupère les conversations avec le dernier message
    // La sous-requête selectionne le message le plus récent de chaque conversation
    $sql = "
        SELECT
            c.id                    AS conv_id,            -- ID de la conversation
            c.patient_id,                                  -- ID du patient
            p.prenom,                                      -- Prénom du patient
            p.nom,                                         -- Nom du patient
            p.telephone,                                   -- Téléphone du patient
            p.groupe_sanguin,                              -- Groupe sanguin
            p.allergies,                                   -- Allergies connues

            -- Dernier message : texte, heure, expéditeur
            m_last.contenu          AS dernier_message,
            m_last.created_at       AS derniere_heure,
            m_last.expediteur       AS dernier_expediteur,

            -- Comptage des messages non lus (envoyés par le patient, non lus par le médecin)
            COUNT(m_unread.id)      AS messages_non_lus

        FROM conversations c

        -- Jointure avec la table patients pour les infos
        INNER JOIN patients p ON p.id = c.patient_id

        -- Sous-requête pour le dernier message de la conversation
        LEFT JOIN (
            SELECT conv_id, contenu, created_at, expediteur
            FROM messages
            WHERE id IN (
                SELECT MAX(id) FROM messages GROUP BY conv_id  -- ID max = dernier message
            )
        ) m_last ON m_last.conv_id = c.id

        -- Jointure pour les messages non lus (venant du patient, non lus par médecin)
        LEFT JOIN messages m_unread
            ON m_unread.conv_id    = c.id
            AND m_unread.expediteur = 'patient'             -- Seulement les messages du patient
            AND m_unread.lu        = 0                      -- Non lus

        WHERE c.medecin_id = :medecin_id                    -- Filtre sur le médecin connecté

        GROUP BY c.id, p.id, m_last.contenu, m_last.created_at, m_last.expediteur

        ORDER BY m_last.created_at DESC                     -- Tri par date décroissante (plus récent en premier)
    ";

    $stmt = $pdo->prepare($sql);              // Prépare la requête (protection injection SQL)
    $stmt->execute([':medecin_id' => $medecinId]); // Exécute avec l'ID du médecin
    $rows = $stmt->fetchAll();               // Récupère tous les résultats

    // Formate les données pour le JSON de sortie
    $conversations = array_map(function ($row) {
        return [
            'id'              => (int)$row['conv_id'],           // ID conversation
            'patient_id'      => (int)$row['patient_id'],        // ID patient
            'patient'         => $row['prenom'] . ' ' . $row['nom'], // Nom complet
            'initiales'       => strtoupper(mb_substr($row['prenom'], 0, 1) . mb_substr($row['nom'], 0, 1)), // Ex: "KK"
            'tel'             => $row['telephone'],              // Téléphone
            'blood'           => $row['groupe_sanguin'],         // Groupe sanguin
            'allergies'       => $row['allergies'],              // Allergies
            'last_message'    => $row['dernier_message'],        // Dernier message
            'last_time'       => formatRelativeTime($row['derniere_heure']), // Heure relative
            'unread'          => (int)$row['messages_non_lus'],  // Nombre non lus
        ];
    }, $rows);

    sendSuccess($conversations); // Retourne le tableau JSON
}

// ============================================================
// FONCTION 2 : RÉCUPÉRER LES MESSAGES D'UNE CONVERSATION
// ============================================================
function getMessages(PDO $pdo, int $medecinId): void {
    // Valide le paramètre conv_id (doit être un entier positif)
    $convId = filter_input(INPUT_GET, 'conv_id', FILTER_VALIDATE_INT);
    if (!$convId || $convId <= 0) {
        sendError(400, "Paramètre conv_id invalide.");
        return;
    }

    // Vérifie que cette conversation appartient bien à ce médecin (sécurité)
    $checkSql = "SELECT id FROM conversations WHERE id = :conv_id AND medecin_id = :medecin_id LIMIT 1";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':conv_id' => $convId, ':medecin_id' => $medecinId]);
    if (!$checkStmt->fetch()) {
        sendError(403, "Accès refusé à cette conversation."); // Conversation non autorisée
        return;
    }

    // Récupère tous les messages de la conversation, triés par date
    $sql = "
        SELECT
            m.id,                           -- ID unique du message
            m.contenu         AS text,      -- Contenu textuel du message
            m.expediteur      AS from_type, -- 'patient' ou 'medecin'
            m.lu,                           -- 1 = lu, 0 = non lu
            m.is_booking,                   -- 1 si c'est une demande de rendez-vous
            m.created_at,                   -- Timestamp de création
            DATE_FORMAT(m.created_at, '%H:%i') AS time,  -- Heure formatée HH:MM
            DATE_FORMAT(m.created_at, '%Y-%m-%d') AS date_raw  -- Date ISO
        FROM messages m
        WHERE m.conv_id = :conv_id          -- Filtre sur la conversation
        ORDER BY m.created_at ASC           -- Ordre chronologique (plus ancien en premier)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':conv_id' => $convId]);
    $messages = $stmt->fetchAll();

    // Formate les messages pour le JSON
    $formatted = array_map(function ($msg) {
        return [
            'id'         => (int)$msg['id'],
            'from'       => $msg['from_type'] === 'medecin' ? 'doctor' : 'patient', // Normalise le nom
            'text'       => $msg['text'],
            'time'       => $msg['time'],                // Heure HH:MM
            'date'       => formatDateLabel($msg['date_raw']), // "Aujourd'hui", "Hier", etc.
            'read'       => (bool)$msg['lu'],            // Booléen PHP → JSON true/false
            'isBooking'  => (bool)$msg['is_booking'],    // Type réservation
        ];
    }, $messages);

    sendSuccess($formatted); // Retourne les messages
}

// ============================================================
// FONCTION 3 : ENVOYER UN MESSAGE
// ============================================================
function envoyerMessage(PDO $pdo, int $medecinId): void {
    // Vérifie que la méthode est bien POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendError(405, "Méthode non autorisée. Utilisez POST.");
        return;
    }

    // Lit le corps de la requête JSON (envoyé par fetch() côté JS)
    $body = file_get_contents('php://input'); // Corps brut de la requête
    $data = json_decode($body, true);         // Décode le JSON en tableau PHP

    // Valide les données reçues
    if (!$data) {
        sendError(400, "Corps de la requête JSON invalide.");
        return;
    }

    $convId  = filter_var($data['conv_id'] ?? 0, FILTER_VALIDATE_INT); // ID conversation
    $contenu = trim($data['message'] ?? '');                            // Texte du message (nettoyé)

    // Vérifie que l'ID et le message sont valides
    if (!$convId || $convId <= 0) {
        sendError(400, "conv_id invalide.");
        return;
    }
    if (empty($contenu)) {
        sendError(400, "Le message ne peut pas être vide.");
        return;
    }
    if (mb_strlen($contenu) > 2000) {
        sendError(400, "Message trop long (max 2000 caractères)."); // Limite de longueur
        return;
    }

    // Vérifie que la conversation appartient au médecin (sécurité)
    $checkStmt = $pdo->prepare("SELECT id FROM conversations WHERE id = :conv_id AND medecin_id = :medecin_id");
    $checkStmt->execute([':conv_id' => $convId, ':medecin_id' => $medecinId]);
    if (!$checkStmt->fetch()) {
        sendError(403, "Accès refusé.");
        return;
    }

    // Insère le message en base de données
    $insertSql = "
        INSERT INTO messages (conv_id, expediteur, contenu, lu, created_at)
        VALUES (:conv_id, 'medecin', :contenu, 1, NOW())
    ";
    // 'medecin' = expéditeur | lu = 1 car le médecin l'envoie lui-même | NOW() = timestamp actuel

    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([
        ':conv_id' => $convId,   // ID de la conversation
        ':contenu' => $contenu   // Texte du message (PDO protège contre l'injection SQL)
    ]);

    $newId = $pdo->lastInsertId(); // Récupère l'ID du message nouvellement inséré

    // Retourne le message créé pour confirmation
    sendSuccess([
        'success'    => true,
        'message_id' => (int)$newId,            // ID du nouveau message
        'time'       => date('H:i'),             // Heure d'envoi
        'date'       => 'Aujourd\'hui'
    ]);
}

// ============================================================
// FONCTION 4 : MARQUER LES MESSAGES COMME LUS
// Appelée quand le médecin ouvre une conversation
// ============================================================
function marquerCommentLu(PDO $pdo, int $medecinId): void {
    // Lit le corps JSON
    $body   = file_get_contents('php://input');
    $data   = json_decode($body, true);
    $convId = filter_var($data['conv_id'] ?? 0, FILTER_VALIDATE_INT);

    if (!$convId || $convId <= 0) {
        sendError(400, "conv_id invalide.");
        return;
    }

    // Vérifie l'appartenance de la conversation au médecin
    $checkStmt = $pdo->prepare("SELECT id FROM conversations WHERE id = :conv_id AND medecin_id = :medecin_id");
    $checkStmt->execute([':conv_id' => $convId, ':medecin_id' => $medecinId]);
    if (!$checkStmt->fetch()) {
        sendError(403, "Accès refusé.");
        return;
    }

    // Met à jour les messages du patient (lu = 1) pour cette conversation
    $updateSql = "
        UPDATE messages
        SET lu = 1, lu_at = NOW()                   -- Marque comme lu avec timestamp
        WHERE conv_id    = :conv_id                  -- Filtre sur la conversation
        AND expediteur   = 'patient'                 -- Seulement les messages du patient
        AND lu           = 0                         -- Seulement les non lus
    ";

    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([':conv_id' => $convId]);
    $nbMaj = $stmt->rowCount(); // Nombre de lignes modifiées

    sendSuccess(['messages_lus' => $nbMaj]); // Confirme le nombre de messages marqués
}

// ============================================================
// FONCTION 5 : NOUVELLES NOTIFICATIONS (POLLING)
// Vérifie s'il y a de nouveaux messages depuis un timestamp
// En production, remplacer par WebSocket pour la performance
// ============================================================
function getNouvellesNotifications(PDO $pdo, int $medecinId): void {
    // Récupère le timestamp depuis lequel vérifier les nouveaux messages
    $since = $_GET['since'] ?? date('Y-m-d H:i:s', strtotime('-1 minute')); // Par défaut : 1 minute avant

    // Requête : nouveaux messages de patients non lus depuis le timestamp
    $sql = "
        SELECT
            m.id,
            m.conv_id,
            m.contenu,
            DATE_FORMAT(m.created_at, '%H:%i') AS time,
            p.prenom, p.nom                           -- Nom du patient pour la notification
        FROM messages m
        INNER JOIN conversations c ON c.id = m.conv_id AND c.medecin_id = :medecin_id
        INNER JOIN patients p ON p.id = c.patient_id
        WHERE m.expediteur  = 'patient'               -- Seulement les messages des patients
        AND   m.lu          = 0                        -- Non lus
        AND   m.created_at  > :since                   -- Après le timestamp donné
        ORDER BY m.created_at ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':medecin_id' => $medecinId, ':since' => $since]);
    $notifications = $stmt->fetchAll();

    // Formate les notifications
    $formatted = array_map(function ($notif) {
        return [
            'conv_id'   => (int)$notif['conv_id'],
            'message'   => mb_substr($notif['contenu'], 0, 80) . (mb_strlen($notif['contenu']) > 80 ? '...' : ''), // Tronqué
            'patient'   => $notif['prenom'] . ' ' . $notif['nom'],
            'time'      => $notif['time'],
        ];
    }, $notifications);

    // Retourne les nouvelles notifications et le timestamp serveur actuel
    sendSuccess([
        'notifications' => $formatted,
        'server_time'   => date('Y-m-d H:i:s'), // Timestamp actuel du serveur (pour le prochain polling)
        'count'         => count($formatted)     // Nombre de nouvelles notifications
    ]);
}

// ============================================================
// FONCTION 6 : STATUT EN LIGNE DU PATIENT
// Vérifie si un patient est actuellement en ligne
// ============================================================
function getStatutPatient(PDO $pdo): void {
    $patientId = filter_input(INPUT_GET, 'patient_id', FILTER_VALIDATE_INT); // ID patient
    if (!$patientId || $patientId <= 0) {
        sendError(400, "patient_id invalide.");
        return;
    }

    // Vérifie si le patient a une session active (connecté dans les 5 dernières minutes)
    $sql = "
        SELECT id, derniere_activite
        FROM patients
        WHERE id = :patient_id
        AND derniere_activite > DATE_SUB(NOW(), INTERVAL 5 MINUTE)  -- Actif dans les 5 min
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':patient_id' => $patientId]);
    $patient = $stmt->fetch();

    sendSuccess([
        'online' => (bool)$patient,             // true si trouvé (actif), false sinon
        'last_seen' => $patient ? formatRelativeTime($patient['derniere_activite']) : null
    ]);
}

// ============================================================
// FONCTIONS UTILITAIRES PHP
// ============================================================

// Retourne un libellé relatif pour une date (Aujourd'hui, Hier, ou la date)
function formatDateLabel(string $dateRaw): string {
    $today     = date('Y-m-d');                     // Date d'aujourd'hui
    $yesterday = date('Y-m-d', strtotime('-1 day')); // Date d'hier

    if ($dateRaw === $today)     return "Aujourd'hui"; // Date = aujourd'hui
    if ($dateRaw === $yesterday) return "Hier";        // Date = hier
    return date('d M Y', strtotime($dateRaw));         // Autre date : format "15 déc. 2024"
}

// Formate un timestamp en heure relative lisible
function formatRelativeTime(string $timestamp): string {
    if (!$timestamp) return ""; // Retourne vide si pas de timestamp

    $diff    = time() - strtotime($timestamp); // Différence en secondes

    if ($diff < 60)     return "maintenant";                        // Moins d'1 minute
    if ($diff < 3600)   return floor($diff / 60) . " min";          // Moins d'1 heure
    if ($diff < 86400)  return date('H:i', strtotime($timestamp));  // Même jour : HH:MM
    if ($diff < 172800) return "Hier";                              // Hier
    return date('d M', strtotime($timestamp));                      // Sinon : "15 déc"
}

// Envoie une réponse JSON de succès (HTTP 200)
function sendSuccess(array $data): void {
    http_response_code(200);          // Code HTTP 200 OK
    echo json_encode([
        'success' => true,            // Indicateur de succès
        'data'    => $data            // Données de la réponse
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // Préserve les accents, format lisible
}

// Envoie une réponse JSON d'erreur avec le code HTTP approprié
function sendError(int $code, string $message): void {
    http_response_code($code);        // Code HTTP (400, 401, 403, 404, 500...)
    echo json_encode([
        'success' => false,           // Indicateur d'échec
        'error'   => $message         // Message d'erreur lisible
    ], JSON_UNESCAPED_UNICODE);
}

/*
============================================================
STRUCTURE SQL REQUISE EN BASE DE DONNÉES
Créer ces tables dans phpMyAdmin ou via MySQL CLI
============================================================

-- Table des conversations
CREATE TABLE conversations (
    id          INT AUTO_INCREMENT PRIMARY KEY,   -- ID unique
    medecin_id  INT NOT NULL,                     -- Médecin propriétaire
    patient_id  INT NOT NULL,                     -- Patient de la conversation
    created_at  DATETIME DEFAULT NOW(),           -- Date de création
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    UNIQUE KEY unique_conv (medecin_id, patient_id) -- Une seule conversation par paire
);

-- Table des messages
CREATE TABLE messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,   -- ID unique
    conv_id     INT NOT NULL,                     -- Conversation parente
    expediteur  ENUM('medecin','patient') NOT NULL, -- Qui envoie
    contenu     TEXT NOT NULL,                    -- Contenu du message
    lu          TINYINT(1) DEFAULT 0,             -- 0=non lu, 1=lu
    lu_at       DATETIME NULL,                    -- Quand lu
    is_booking  TINYINT(1) DEFAULT 0,             -- 1 si demande de rendez-vous
    created_at  DATETIME DEFAULT NOW(),           -- Date d'envoi
    FOREIGN KEY (conv_id) REFERENCES conversations(id) ON DELETE CASCADE,
    INDEX idx_conv_created (conv_id, created_at)  -- Index pour performance
);

-- Colonne à ajouter à la table patients (si pas déjà présente)
ALTER TABLE patients ADD COLUMN derniere_activite DATETIME NULL;

============================================================
EXEMPLE D'UTILISATION DEPUIS messagerie.js (fetch API)
============================================================

// Récupérer la liste des conversations
fetch('messagerie.php?action=liste_conversations')
    .then(r => r.json())
    .then(data => { if (data.success) renderConvList(data.data); });

// Envoyer un message
fetch('messagerie.php?action=envoyer', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ conv_id: 1, message: "Bonjour patient !" })
}).then(r => r.json()).then(console.log);

// Polling pour nouvelles notifications (toutes les 15 secondes)
setInterval(() => {
    fetch('messagerie.php?action=nouvelles_notifications&since=' + lastPollTime)
        .then(r => r.json())
        .then(data => { if (data.data.count > 0) handleNewNotifs(data.data); });
}, 15000);

============================================================
*/
?>