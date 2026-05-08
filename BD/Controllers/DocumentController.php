<?php
// controllers/DocumentController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';

class DocumentController {

    // ── GET /api/patients/{id}/documents ──────────────────────
    public static function liste(int $patientId): void {
        $auth = Auth::requireAuth();
        $db   = Database::getInstance();

        $stmt = $db->prepare("
            SELECT d.*, CONCAT(u.prenom,' ',u.nom) AS uploade_par
            FROM documents d
            JOIN utilisateurs u ON u.id = d.uploader_id
            WHERE d.patient_id = ?
            ORDER BY d.created_at DESC
        ");
        $stmt->execute([$patientId]);
        Response::success($stmt->fetchAll());
    }

    // ── POST /api/patients/{id}/documents ─────────────────────
    public static function upload(int $patientId): void {
        $auth = Auth::requireAuth();

        if (empty($_FILES['fichier'])) Response::error('Aucun fichier fourni', 422);
        if (empty($_POST['titre']))    Response::error('Titre requis', 422);

        $allowed = ['application/pdf','image/jpeg','image/png','image/gif'];
        $file    = $_FILES['fichier'];
        if (!in_array($file['type'], $allowed)) Response::error('Format non autorisé', 422);
        if ($file['size'] > 10 * 1024 * 1024)  Response::error('Max 10MB', 422);

        $dir = UPLOAD_DIR . 'documents/' . $patientId . '/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = bin2hex(random_bytes(12)) . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $dir . $name);
        $url = BASE_URL . '/uploads/documents/' . $patientId . '/' . $name;

        $db   = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO documents (patient_id, consultation_id, titre, type, fichier_url, taille_ko, uploader_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $patientId,
            $_POST['consultation_id'] ?? null,
            $_POST['titre'],
            $_POST['type'] ?? 'autre',
            $url,
            round($file['size'] / 1024),
            $auth['user_id'],
        ]);

        Response::success(['id' => $db->lastInsertId(), 'url' => $url], 201);
    }

    // ── DELETE /api/documents/{id} ────────────────────────────
    public static function supprimer(int $id): void {
        $auth = Auth::requireRole('medecin', 'admin');
        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT fichier_url FROM documents WHERE id = ?");
        $stmt->execute([$id]);
        $doc = $stmt->fetch();
        if (!$doc) Response::error('Document introuvable', 404);

        // Supprimer le fichier physique
        $path = str_replace(BASE_URL, rtrim(UPLOAD_DIR, '/') . '/..', $doc['fichier_url']);
        if (file_exists($path)) unlink($path);

        $db->prepare("DELETE FROM documents WHERE id = ?")->execute([$id]);
        Response::success(['message' => 'Document supprimé']);
    }
}