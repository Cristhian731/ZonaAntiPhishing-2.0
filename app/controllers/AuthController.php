<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public const MIN_PASSWORD_LENGTH = 8;

    private User $userModel;

    public static function hasValidPasswordLength(string $password): bool
    {
        $length = preg_match_all('/./us', $password);

        return $length !== false && $length >= self::MIN_PASSWORD_LENGTH;
    }

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register(
        string $name,
        string $email,
        string $password
    ): bool {
        $name = trim($name);
        $email = trim($email);

        if (
            $name === ''
            || $email === ''
            || filter_var($email, FILTER_VALIDATE_EMAIL) === false
            || $password === ''
            || !self::hasValidPasswordLength($password)
        ) {
            return false;
        }

        if ($this->userModel->findByEmail($email)) {
            return false;
        }

        return $this->userModel->createUser(
            $name,
            $email,
            $password
        );
    }

    public function login(
        string $email,
        string $password
    ) {

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return false;
        }

        if ((int) ($user['is_active'] ?? 0) !== 1) {
            return false;
        }

        if (
            password_verify(
                $password,
                $user['password_hash']
            )
        ) {
            $this->userModel->updateLastLogin((int) $user['id']);
            return $user;
        }

        return false;
    }
}