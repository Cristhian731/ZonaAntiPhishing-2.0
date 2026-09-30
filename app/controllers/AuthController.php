<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register(
        string $name,
        string $email,
        string $password
    ): bool {

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

        if (
            password_verify(
                $password,
                $user['password_hash']
            )
        ) {
            return $user;
        }

        return false;
    }
}