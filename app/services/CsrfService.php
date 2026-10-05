<?php

class CsrfService
{
    public static function generateToken(): string
    {
        self::ensureSession();

        if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function regenerateToken(): string
    {
        self::ensureSession();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        return $_SESSION['csrf_token'];
    }

    public static function validateToken(mixed $token): bool
    {
        self::ensureSession();

        $sessionToken = $_SESSION['csrf_token'] ?? null;

        if (!is_string($token) || $token === '' || !is_string($sessionToken) || $sessionToken === '') {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    private static function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}