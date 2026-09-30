<?php

require_once __DIR__ . '/../../config/database.php';

class UserProgress
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function markLessonCompleted(int $userId, int $lessonId): bool
    {
        $selectSql = "SELECT id FROM user_progress
					  WHERE user_id = :user_id AND lesson_id = :lesson_id
					  LIMIT 1";

        $selectStmt = $this->conn->prepare($selectSql);
        $selectStmt->execute([
            'user_id' => $userId,
            'lesson_id' => $lessonId,
        ]);

        if ($selectStmt->fetch(PDO::FETCH_ASSOC)) {
            $sql = "UPDATE user_progress
					SET completed = 1, completed_at = CURRENT_TIMESTAMP
					WHERE user_id = :user_id AND lesson_id = :lesson_id";
        } else {
            $sql = "INSERT INTO user_progress (user_id, lesson_id, completed, completed_at)
					VALUES (:user_id, :lesson_id, 1, CURRENT_TIMESTAMP)";
        }

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'lesson_id' => $lessonId,
        ]);
    }
}
