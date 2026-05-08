<?php
// controllers/VaccinationController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';

class VaccinationController {

    // ── GET /api/vaccins ──────────────────────────────────────
    // Liste tous les vaccins du référentiel
    public static function listVaccins(): void {
        Auth::requireAuth();
        $db   = Database::getInstance();
        $rows = $db->query("SELECT * FROM vaccins ORDER BY obligatoire DESC, nom ASC")->fetchAll();
        Response::success($rows);
    }

    // ── GET /api/patients/{id}/vaccinations ───────────────────
    // Carnet vaccinal complet d'un patient
    public static function carnetPatient(int $patientId): void {
        $auth = Auth::requireAuth();
        self::checkAccess($auth, $patientId);

        $db   = Database::getInstance();
        $stmt = $db->prepare("
            SELECT v.*, vac.nom AS vaccin_nom, vac.fabricant,
                   CONCAT(u.prenom, ' ', u.nom) AS medecin_nom
            FROM vaccinations v
            JOIN vaccins vac ON vac.id = v.vaccin_id
            LEFT JOIN medecins m ON m.id = v.medecin_id
            LEFT JOIN utilisateurs u ON u.id = m.user_id
            WHERE v.patient_id = ?
            ORDER BY v.date_injection DESC
        ");
        $stmt->execute([$patientId]);
        Response::success($stmt->fetchAll());
    }

    // ── POST /api/patients/{id}/vaccinations ──────────────────
    // Ajouter une vaccination au carnet
    public static function ajouter(int $patientId): void {
        $auth = Auth::requireRole('medecin', 'admin');
        $data = json_decode(file_get_contents('php://input'), true);

        $required = ['vaccin_id', 'date_injection', 'numero_dose'];
        foreach ($required as $field) {
            if (empty($data[$field])) Response::error("Champ requis : $field", 422);
        }

        $db   = Database::getInstance();

        // Récupérer medecin_id si role = medecin
        $medecinId = null;
        if ($auth['role'] === 'medecin') {
            $stmt = $db->prepare("SELECT id FROM medecins WHERE user_id = ?");
            $stmt->execute([$auth['user_id']]);
            $med = $stmt->fetch();
            $medecinId = $med['id'] ?? null;
        }

        $stmt = $db->prepare("
            INSERT INTO vaccinations
                (patient_id, vaccin_id, medecin_id, numero_dose, date_injection, date_rappel, lot_vaccin, centre, observations)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $patientId,
            $data['vaccin_id'],
            $medecinId,
            $data['numero_dose'],
            $data['date_injection'],
            $data['date_rappel'] ?? null,
            $data['lot_vaccin'] ?? null,
            $data['centre'] ?? null,
            $data['observations'] ?? null,
        ]);
        $id = $db->lastInsertId();

        // Créer rappel si date_rappel fournie
        if (!empty($data['date_rappel'])) {
            $stmt = $db->prepare("INSERT INTO rappels_vaccins (patient_id, vaccin_id, date_rappel) VALUES (?, ?, ?)");
            $stmt->execute([$patientId, $data['vaccin_id'], $data['date_rappel']]);
        }

        Response::success(['id' => $id, 'message' => 'Vaccination enregistrée'], 201);
    }

    // ── DELETE /api/vaccinations/{id} ─────────────────────────
    public static function supprimer(int $vaccinationId): void {
        Auth::requireRole('medecin', 'admin');
        $db   = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM vaccinations WHERE id = ?");
        $stmt->execute([$vaccinationId]);
        Response::success(['message' => 'Supprimé avec succès']);
    }

    // ── GET /api/patients/{id}/rappels ────────────────────────
    // Rappels vaccins à venir
    public static function rappels(int $patientId): void {
        $auth = Auth::requireAuth();
        self::checkAccess($auth, $patientId);

        $db   = Database::getInstance();
        $stmt = $db->prepare("
            SELECT r.*, vac.nom AS vaccin_nom
            FROM rappels_vaccins r
            JOIN vaccins vac ON vac.id = r.vaccin_id
            WHERE r.patient_id = ? AND r.date_rappel >= CURDATE()
            ORDER BY r.date_rappel ASC
        ");
        $stmt->execute([$patientId]);
        Response::success($stmt->fetchAll());
    }

    // ── GET /api/vaccinations/stats ───────────────────────────
    // Stats globales (admin)
    public static function stats(): void {
        Auth::requireRole('admin');
        $db  = Database::getInstance();
        $res = $db->query("
            SELECT v.nom, COUNT(vac.id) AS total_doses
            FROM vaccins v
            LEFT JOIN vaccinations vac ON vac.vaccin_id = v.id
            GROUP BY v.id
            ORDER BY total_doses DESC
        ")->fetchAll();
        Response::success($res);
    }

    // ── Vérification accès patient ────────────────────────────
    private static function checkAccess(array $auth, int $patientId): void {
        if ($auth['role'] === 'admin') return;
        if ($auth['role'] === 'medecin') return;

        // Patient : vérifier que c'est le sien
        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM patients WHERE id = ? AND user_id = ?");
        $stmt->execute([$patientId, $auth['user_id']]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo json_encode(['erreur' => 'Accès interdit']);
            exit;
        }
    }
}