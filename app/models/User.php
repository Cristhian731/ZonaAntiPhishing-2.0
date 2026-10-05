<?php

require_once __DIR__ . '/../../config/database.php';

class User
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':email', $email);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser(
        string $name,
        string $email,
        string $password
    ): bool {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO users
                (
                    name,
                    email,
                    password_hash
                )
                VALUES
                (
                    :name,
                    :email,
                    :password_hash
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $hashedPassword
        ]);
    }

    public function updateLastLogin(int $userId): void
    {
        $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $userId]);
    }
}
