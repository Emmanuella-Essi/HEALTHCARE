<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class PatientController
{
    public static function index(): void
    {
        $db = Database::getInstance();
        $stmt = $db->query("
            SELECT
                p.id,
                u.nom,
                u.prenom,
                u.email,
                u.telephone,
                p.date_naissance,
                p.sexe,
                p.groupe_sanguin,
                p.allergies,
                p.adresse,
                p.ville,
                u.created_at
            FROM patients p
            INNER JOIN utilisateurs u ON u.id = p.user_id
            ORDER BY u.nom, u.prenom
        ");

        Response::success($stmt->fetchAll());
    }

    public static function show(int $id): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT
                p.*,
                u.nom,
                u.prenom,
                u.email,
                u.telephone,
                u.photo_profil,
                u.created_at
            FROM patients p
            INNER JOIN utilisateurs u ON u.id = p.user_id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $patient = $stmt->fetch();

        if (!$patient) {
            Response::error('Patient introuvable', 404);
        }

        Response::success($patient);
    }

    public static function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = Validator::check($data, [
            'nom' => 'required|min:2',
            'prenom' => 'required|min:2',
            'email' => 'required|email',
            'date_naissance' => 'required',
            'sexe' => 'required|in:M,F,Autre',
        ]);

        if ($errors) {
            Response::error($errors, 422);
        }

        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                $db->rollBack();
                Response::error('Email deja utilise', 409);
            }

            $temporaryPassword = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT);
            $stmt = $db->prepare("
                INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, telephone)
                VALUES (?, ?, ?, ?, 'patient', ?)
            ");
            $stmt->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $temporaryPassword,
                $data['telephone'] ?? null,
            ]);

            $userId = (int) $db->lastInsertId();
            $stmt = $db->prepare("
                INSERT INTO patients (
                    user_id,
                    date_naissance,
                    sexe,
                    groupe_sanguin,
                    adresse,
                    ville,
                    allergies
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId,
                $data['date_naissance'],
                $data['sexe'],
                $data['groupe_sanguin'] ?? null,
                $data['adresse'] ?? null,
                $data['ville'] ?? null,
                $data['allergies'] ?? null,
            ]);

            $patientId = (int) $db->lastInsertId();
            $db->commit();

            Response::success([
                'message' => 'Patient ajoute avec succes',
                'id' => $patientId,
            ], 201);
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            Response::error('Erreur serveur : ' . $e->getMessage(), 500);
        }
    }
}
