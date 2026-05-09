<?php
// ============================================================
// traitement_messagerie.php — Traitement des formulaires messagerie
// Gère : envoyer message, créer conversation, marquer lu, supprimer
// ============================================================

require_once 'config.php';

header('Content-Type: application/json; charset=UTF-8');
$mid    = requireAuth(true);
$pdo    = getDb();
$raw    = file_get_contents('php://input');
$data   = json_decode($raw, true) ?? [];
$action = clean($_GET['action'] ?? $data['action'] ?? '');

// ============================================================
// ROUTEUR
// ============================================================
switch ($action) {
    case 'creer_conversation':    creerConversation($pdo, $mid, $data);    break;
    case 'envoyer_message':       envoyerMessage($pdo, $mid, $data);       break;
    case 'marquer_lu':            marquerLu($pdo, $mid, $data);            break;
    case 'supprimer_message':     supprimerMessage($pdo, $mid, $data);     break;
    case 'supprimer_conversation':supprimerConversation($pdo, $mid, $data);break;
    default: jsonError(400, "Action '$action' inconnue.");
}

// ============================================================
// CRÉER OU RETROUVER UNE CONVERSATION
// body: {patient_id}
// ============================================================
function creerConversation(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    if ($pid <= 0) jsonError(400,"patient_id invalide.");

    // Vérifie que ce patient appartient au médecin
    $chk = $pdo->prepare("SELECT id FROM patients WHERE id=:pid AND medecin_id=:mid AND supprime=0 LIMIT 1");
    $chk->execute([':pid'=>$pid,':mid'=>$mid]);
    if (!$chk->fetch()) jsonError(403,"Patient introuvable ou accès refusé.");

    // Cherche une conversation existante entre ce médecin et ce patient
    $existing = $pdo->prepare("SELECT id FROM conversations WHERE medecin_id=:mid AND patient_id=:pid LIMIT 1");
    $existing->execute([':mid'=>$mid,':pid'=>$pid]);
    $conv = $existing->fetch();

    if ($conv) {
        // Conversation existante → retourne son ID
        jsonSuccess(['conversation_id'=>(int)$conv['id'],'created'=>false],
            "Conversation existante retrouvée.");
    }

    // Crée une nouvelle conversation
    $pdo->prepare("INSERT INTO conversations (medecin_id, patient_id, created_at) VALUES (:mid,:pid,NOW())")
        ->execute([':mid'=>$mid,':pid'=>$pid]);

    $convId = (int)$pdo->lastInsertId();
    jsonSuccess(['conversation_id'=>$convId,'created'=>true],"Nouvelle conversation créée.");
}

// ============================================================
// ENVOYER UN MESSAGE
// body: {conv_id, message, is_booking?}
// ============================================================
function envoyerMessage(PDO $pdo, int $mid, array $d): void {
    $convId    = (int)($d['conv_id']    ?? 0);
    $contenu   = trim($d['message']     ?? '');
    $isBooking = (int)($d['is_booking'] ?? 0); // 1 si demande de rendez-vous

    if ($convId <= 0)     jsonError(400,"conv_id invalide.");
    if (empty($contenu))  jsonError(422,"Le message ne peut pas être vide.");
    if (mb_strlen($contenu) > 3000) jsonError(422,"Message trop long (max 3000 caractères).");

    // Vérifie que la conversation appartient à ce médecin
    if (!convAppartient($pdo,$convId,$mid)) jsonError(403,"Accès refusé à cette conversation.");

    // Insère le message dans la BDD
    $sql = "INSERT INTO messages (conv_id, expediteur, contenu, lu, is_booking, created_at)
            VALUES (:conv, 'medecin', :contenu, 1, :booking, NOW())";
    // lu=1 : le médecin lit son propre message immédiatement
    $pdo->prepare($sql)->execute([
        ':conv'    => $convId,
        ':contenu' => $contenu,
        ':booking' => $isBooking ? 1 : 0,
    ]);

    $msgId = (int)$pdo->lastInsertId();

    // Met à jour la date de dernière activité de la conversation
    $pdo->prepare("UPDATE conversations SET updated_at=NOW() WHERE id=:cid")
        ->execute([':cid'=>$convId]);

    jsonSuccess([
        'message_id' => $msgId,
        'time'       => date('H:i'),      // Heure formatée pour l'affichage immédiat
        'date'       => "Aujourd'hui",
    ],"Message envoyé.");
}

// ============================================================
// MARQUER LES MESSAGES D'UNE CONVERSATION COMME LUS
// body: {conv_id}
// ============================================================
function marquerLu(PDO $pdo, int $mid, array $d): void {
    $convId = (int)($d['conv_id'] ?? 0);
    if ($convId <= 0) jsonError(400,"conv_id invalide.");
    if (!convAppartient($pdo,$convId,$mid)) jsonError(403,"Accès refusé.");

    // Marque comme lus tous les messages du patient non encore lus
    $stmt = $pdo->prepare("
        UPDATE messages
        SET lu=1, lu_at=NOW()
        WHERE conv_id=:cid AND expediteur='patient' AND lu=0
    ");
    $stmt->execute([':cid'=>$convId]);

    jsonSuccess(['messages_lus'=>$stmt->rowCount()],"Messages marqués lus.");
}

// ============================================================
// SUPPRIMER UN MESSAGE
// body: {message_id, conv_id}
// ============================================================
function supprimerMessage(PDO $pdo, int $mid, array $d): void {
    $msgId  = (int)($d['message_id'] ?? 0);
    $convId = (int)($d['conv_id']    ?? 0);

    if ($msgId <= 0 || $convId <= 0) jsonError(400,"IDs invalides.");
    if (!convAppartient($pdo,$convId,$mid)) jsonError(403,"Accès refusé.");

    // Vérifie que le message appartient à cette conversation et vient du médecin
    $chk = $pdo->prepare("SELECT id FROM messages WHERE id=:mid AND conv_id=:cid AND expediteur='medecin' LIMIT 1");
    $chk->execute([':mid'=>$msgId,':cid'=>$convId]);
    if (!$chk->fetch()) jsonError(403,"Message introuvable ou non supprimable.");

    $pdo->prepare("DELETE FROM messages WHERE id=:mid")->execute([':mid'=>$msgId]);
    jsonSuccess(['message_id'=>$msgId],"Message supprimé.");
}

// ============================================================
// SUPPRIMER UNE CONVERSATION (archive tous les messages)
// body: {conv_id}
// ============================================================
function supprimerConversation(PDO $pdo, int $mid, array $d): void {
    $convId = (int)($d['conv_id'] ?? 0);
    if ($convId <= 0) jsonError(400,"conv_id invalide.");
    if (!convAppartient($pdo,$convId,$mid)) jsonError(403,"Accès refusé.");

    // Supprime les messages puis la conversation (CASCADE géré par FK)
    $pdo->prepare("DELETE FROM conversations WHERE id=:cid")->execute([':cid'=>$convId]);
    jsonSuccess(['conv_id'=>$convId],"Conversation supprimée.");
}

// ---- Vérifie qu'une conversation appartient au médecin ----
function convAppartient(PDO $pdo, int $convId, int $mid): bool {
    $s = $pdo->prepare("SELECT id FROM conversations WHERE id=:cid AND medecin_id=:mid LIMIT 1");
    $s->execute([':cid'=>$convId,':mid'=>$mid]);
    return (bool)$s->fetch();
}