<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public const MIN_PASSWORD_LENGTH = 8;
    public const MIN_NAME_LENGTH = 2;
    public const MAX_NAME_LENGTH = 100;
    private const LOGIN_ATTEMPT_LIMIT = 5;

    private User $userModel;

    public static function nameLength(string $name): ?int
    {
        $length = preg_match_all('/./us', $name);

        return $length === false ? null : $length;
    }

    public static function hasValidNameLength(string $name): bool
    {
        $length = self::nameLength($name);

        return $length !== null
            && $length >= self::MIN_NAME_LENGTH
            && $length <= self::MAX_NAME_LENGTH;
    }

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
            !self::hasValidNameLength($name)
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
        string $password,
        ?string $clientIp = null
    ) {
        $attemptKey = $this->getLoginAttemptKey($email, $clientIp);

        if ($this->userModel->getLoginBlockSeconds($attemptKey) > 0) {
            return false;
        }

        $user = $this->userModel->findByEmail($email);

        if (
            $user
            && (int) ($user['is_active'] ?? 0) === 1
            && password_verify(
                $password,
                $user['password_hash']
            )
        ) {
            $this->userModel->updateLastLogin((int) $user['id']);
            $this->userModel->clearLoginAttempts($attemptKey);
            return $user;
        }

        $this->userModel->recordFailedLoginAttempt($attemptKey, self::LOGIN_ATTEMPT_LIMIT);

        return false;
    }

    public function getLoginBlockSeconds(string $email, ?string $clientIp = null): int
    {
        return $this->userModel->getLoginBlockSeconds($this->getLoginAttemptKey($email, $clientIp));
    }

    private function getLoginAttemptKey(string $email, ?string $clientIp): string
    {
        $clientIp = $clientIp ?? (string) ($_SERVER['REMOTE_ADDR'] ?? '');

        return hash('sha256', strtolower(trim($email)) . "\0" . $clientIp);
    }
}