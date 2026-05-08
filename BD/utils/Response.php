<?php
// utils/Response.php

class Response {
    public static function success(mixed $data, int $code = 200): void {
        http_response_code($code);
        echo json_encode(['succes' => true, 'data' => $data]);
        exit;
    }

    public static function error(mixed $message, int $code = 400): void {
        http_response_code($code);
        echo json_encode(['succes' => false, 'erreur' => $message]);
        exit;
    }
}