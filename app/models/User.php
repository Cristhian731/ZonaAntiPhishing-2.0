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

    public function getLoginBlockSeconds(string $attemptKey): int
    {
        $sql = "SELECT GREATEST(
                    0,
                    TIMESTAMPDIFF(SECOND, CURRENT_TIMESTAMP, blocked_until)
                )
                FROM login_attempts
                WHERE attempt_key = :attempt_key
                  AND blocked_until IS NOT NULL";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['attempt_key' => $attemptKey]);
        $remainingSeconds = $stmt->fetchColumn();

        return $remainingSeconds === false ? 0 : (int) $remainingSeconds;
    }

    public function recordFailedLoginAttempt(string $attemptKey, int $attemptLimit): void
    {
        $this->conn->beginTransaction();

        try {
            $insert = $this->conn->prepare(
                "INSERT IGNORE INTO login_attempts
                    (attempt_key, failed_attempts, first_attempt_at)
                 VALUES (:attempt_key, 0, CURRENT_TIMESTAMP)"
            );
            $insert->execute(['attempt_key' => $attemptKey]);

            $select = $this->conn->prepare(
                "SELECT failed_attempts,
                        first_attempt_at < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 15 MINUTE) AS window_expired
                 FROM login_attempts
                 WHERE attempt_key = :attempt_key
                 FOR UPDATE"
            );
            $select->execute(['attempt_key' => $attemptKey]);
            $attempt = $select->fetch(PDO::FETCH_ASSOC);

            if (!is_array($attempt)) {
                throw new RuntimeException('Unable to read login attempt state.');
            }

            $windowExpired = (int) $attempt['window_expired'] === 1;
            $failedAttempts = $windowExpired ? 1 : (int) $attempt['failed_attempts'] + 1;
            $update = $this->conn->prepare(
                "UPDATE login_attempts
                 SET failed_attempts = :failed_attempts,
                     first_attempt_at = CASE
                         WHEN :window_expired = 1 THEN CURRENT_TIMESTAMP
                         ELSE first_attempt_at
                     END,
                     blocked_until = CASE
                         WHEN :is_blocked = 1 THEN DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 15 MINUTE)
                         ELSE NULL
                     END
                 WHERE attempt_key = :attempt_key"
            );
            $update->execute([
                'failed_attempts' => $failedAttempts,
                'window_expired' => (int) $windowExpired,
                'is_blocked' => (int) ($failedAttempts >= $attemptLimit),
                'attempt_key' => $attemptKey,
            ]);

            $this->conn->commit();
        } catch (Throwable $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $exception;
        }
    }

    public function clearLoginAttempts(string $attemptKey): void
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM login_attempts WHERE attempt_key = :attempt_key"
        );
        $stmt->execute(['attempt_key' => $attemptKey]);
    }
}
