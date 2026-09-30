<?php

require_once __DIR__ . '/../../config/database.php';

class Dashboard
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getMetricsForUser(int $userId): array
    {
        $sql = "SELECT
                    (SELECT COUNT(*) FROM courses) AS courses_available,
                    (SELECT COUNT(*) FROM user_progress
                     WHERE user_id = :progress_user_id AND completed = 1) AS lessons_completed,
                    (SELECT COUNT(*) FROM quiz_attempts
                     WHERE user_id = :attempt_user_id AND passed = 1) AS quizzes_passed,
                    (SELECT COUNT(*) FROM lessons) AS total_lessons";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'progress_user_id' => $userId,
            'attempt_user_id' => $userId,
        ]);

        $metrics = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($metrics) ? $metrics : [];
    }
}
