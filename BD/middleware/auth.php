<?php
// middleware/Auth.php

require_once __DIR__ . '/../config/database.php';

class Auth {

    // ── Générer un token JWT ──────────────────────────────────
    public static function generateToken(array $payload): string {
        $header  = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['iat'] = time();
        $payload['exp'] = time() + JWT_EXPIRE;
        $body    = base64_encode(json_encode($payload));
        $sig     = hash_hmac('sha256', "$header.$body", JWT_SECRET, true);
        $sigB64  = base64_encode($sig);
        return "$header.$body.$sigB64";
    }

    // ── Vérifier et décoder un token ─────────────────────────
    public static function verifyToken(string $token): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        [$header, $body, $sig] = $parts;
        $expected = base64_encode(hash_hmac('sha256', "$header.$body", JWT_SECRET, true));

        if (!hash_equals($expected, $sig)) return null;

        $payload = json_decode(base64_decode($body), true);
        if (!$payload || $payload['exp'] < time()) return null;

        // Vérifier blacklist
        $db   = Database::getInstance();
        $hash = hash('sha256', $token);
        $stmt = $db->prepare("SELECT id FROM tokens_blacklist WHERE token_hash = ? AND expire_le > NOW()");
        $stmt->execute([$hash]);
        if ($stmt->fetch()) return null;

        return $payload;
    }

    // ── Middleware : extraire l'utilisateur courant ───────────
    public static function requireAuth(): array {
        $headers = getallheaders();
        $auth    = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (!preg_match('/Bearer\s+(.+)/i', $auth, $m)) {
            self::unauthorized('Token manquant');
        }

        $payload = self::verifyToken($m[1]);
        if (!$payload) self::unauthorized('Token invalide ou expiré');

        return $payload;
    }

    // ── Middleware : rôle requis ──────────────────────────────
    public static function requireRole(string ...$roles): array {
        $user = self::requireAuth();
        if (!in_array($user['role'], $roles)) {
            http_response_code(403);
            echo json_encode(['erreur' => 'Accès interdit']);
            exit;
        }
        return $user;
    }

    // ── Révoquer un token (logout) ────────────────────────────
    public static function revokeToken(string $token): void {
        $payload = self::verifyToken($token);
        if (!$payload) return;
        $db   = Database::getInstance();
        $hash = hash('sha256', $token);
        $stmt = $db->prepare("INSERT IGNORE INTO tokens_blacklist (token_hash, expire_le) VALUES (?, FROM_UNIXTIME(?))");
        $stmt->execute([$hash, $payload['exp']]);
    }

    private static function unauthorized(string $msg): void {
        http_response_code(401);
        echo json_encode(['erreur' => $msg]);
        exit;
    }
}