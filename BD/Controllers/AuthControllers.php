<?php
// controllers/AuthController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class AuthController {

    // ── POST /api/auth/register ───────────────────────────────
    public static function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $errors = Validator::check($data, [
            'nom'          => 'required|min:2',
            'prenom'       => 'required|min:2',
            'email'        => 'required|email',
            'mot_de_passe' => 'required|min:8',
            'role'         => 'in:patient,medecin',
        ]);
        if ($errors) Response::error($errors, 422);

        $db = Database::getInstance();

        // Email unique
        $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) Response::error('Email déjà utilisé', 409);

        $hash = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT, ['cost' => 12]);
        $role = $data['role'] ?? 'patient';

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("
                INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, telephone)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['nom'], $data['prenom'], $data['email'],
                $hash, $role, $data['telephone'] ?? null
            ]);
            $userId = $db->lastInsertId();

            // Créer le profil associé
            if ($role === 'patient') {
                $stmt = $db->prepare("
                    INSERT INTO patients (user_id, date_naissance, sexe, groupe_sanguin, adresse, ville)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $userId,
                    $data['date_naissance'] ?? '1990-01-01',
                    $data['sexe'] ?? 'M',
                    $data['groupe_sanguin'] ?? null,
                    $data['adresse'] ?? null,
                    $data['ville'] ?? null,
                ]);
            } elseif ($role === 'medecin') {
                $stmt = $db->prepare("
                    INSERT INTO medecins (user_id, numero_ordre, specialite, hopital, ville)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $userId,
                    $data['numero_ordre'] ?? '',
                    $data['specialite'] ?? 'Médecin généraliste',
                    $data['hopital'] ?? null,
                    $data['ville'] ?? null,
                ]);
            }

            $db->commit();

            $token = Auth::generateToken([
                'user_id' => $userId,
                'email'   => $data['email'],
                'role'    => $role,
            ]);

            Response::success(['token' => $token, 'role' => $role, 'user_id' => $userId], 201);
        } catch (Exception $e) {
            $db->rollBack();
            Response::error('Erreur serveur : ' . $e->getMessage(), 500);
        }
    }

    // ── POST /api/auth/login ──────────────────────────────────
    public static function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = Validator::check($data, [
            'email'        => 'required|email',
            'mot_de_passe' => 'required',
        ]);
        if ($errors) Response::error($errors, 422);

        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = ? AND est_actif = 1");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($data['mot_de_passe'], $user['mot_de_passe'])) {
            Response::error('Email ou mot de passe incorrect', 401);
        }

        $token = Auth::generateToken([
            'user_id' => $user['id'],
            'email'   => $user['email'],
            'role'    => $user['role'],
        ]);

        unset($user['mot_de_passe'], $user['token_reset']);
        Response::success(['token' => $token, 'utilisateur' => $user]);
    }

    // ── POST /api/auth/logout ─────────────────────────────────
    public static function logout(): void {
        $headers = getallheaders();
        $auth    = $headers['Authorization'] ?? '';
        preg_match('/Bearer\s+(.+)/i', $auth, $m);
        if (!empty($m[1])) Auth::revokeToken($m[1]);
        Response::success(['message' => 'Déconnexion réussie']);
    }

    // ── GET /api/auth/me ──────────────────────────────────────
    public static function me(): void {
        $authUser = Auth::requireAuth();
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, nom, prenom, email, role, telephone, photo_profil, created_at FROM utilisateurs WHERE id = ?");
        $stmt->execute([$authUser['user_id']]);
        $user = $stmt->fetch();
        if (!$user) Response::error('Utilisateur introuvable', 404);
        Response::success($user);
    }
}