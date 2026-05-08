<?php
// controllers/MessageController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';

class MessageController {

    // ── GET /api/consultations/{id}/messages ──────────────────
    public static function liste(int $consultationId): void {
        $auth = Auth::requireAuth();
        $db   = Database::getInstance();

        $stmt = $db->prepare("
            SELECT m.*, CONCAT(u.prenom,' ',u.nom) AS expediteur_nom, u.role AS expediteur_role
            FROM messages m
            JOIN utilisateurs u ON u.id = m.expediteur_id
            WHERE m.consultation_id = ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$consultationId]);
        $messages = $stmt->fetchAll();

        // Marquer comme lus
        $db->prepare("UPDATE messages SET lu = 1 WHERE consultation_id = ? AND expediteur_id != ?")
           ->execute([$consultationId, $auth['user_id']]);

        Response::success($messages);
    }

    // ── POST /api/consultations/{id}/messages ─────────────────
    public static function envoyer(int $consultationId): void {
        $auth = Auth::requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['contenu']) && empty($_FILES['fichier'])) {
            Response::error('Message vide', 422);
        }

        $db  = Database::getInstance();
        $url = null;

        // Upload fichier optionnel
        if (!empty($_FILES['fichier'])) {
            $url = self::uploadFichier($_FILES['fichier']);
        }

        $stmt = $db->prepare("
            INSERT INTO messages (consultation_id, expediteur_id, contenu, type, fichier_url)
            VALUES (?, ?, ?, ?, ?)
        ");
        $type = $url ? 'fichier' : 'texte';
        $stmt->execute([$consultationId, $auth['user_id'], $data['contenu'] ?? '', $type, $url]);

        Response::success(['id' => $db->lastInsertId(), 'message' => 'Message envoyé'], 201);
    }

    // ── Upload fichier ────────────────────────────────────────
    private static function uploadFichier(array $file): string {
        $allowed = ['image/jpeg','image/png','image/gif','application/pdf'];
        if (!in_array($file['type'], $allowed)) Response::error('Type de fichier non autorisé', 422);
        if ($file['size'] > 5 * 1024 * 1024) Response::error('Fichier trop volumineux (max 5MB)', 422);

        $dir = UPLOAD_DIR . 'messages/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = bin2hex(random_bytes(16)) . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $dir . $name);

        return BASE_URL . '/uploads/messages/' . $name;
    }
}