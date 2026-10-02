<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/CertificateProgress.php';

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

        $saved = $stmt->execute([
            'user_id' => $userId,
            'lesson_id' => $lessonId,
        ]);

        if (!$saved) {
            return false;
        }

        try {
            $courseStmt = $this->conn->prepare(
                'SELECT course_id FROM lessons WHERE id = :lesson_id LIMIT 1'
            );
            $courseStmt->execute([
                'lesson_id' => $lessonId,
            ]);
            $courseId = (int) $courseStmt->fetchColumn();

            if ($courseId > 0) {
                $certificateProgress = new CertificateProgress();

                if (
                    $certificateProgress->isCourseCompleted($userId, $courseId)
                    && !$certificateProgress->certificateExists($userId, $courseId)
                ) {
                    $certificateProgress->generateCertificate($userId, $courseId);
                }
            }
        } catch (Throwable $exception) {
            error_log('Certificate generation failed: ' . $exception->getMessage());
        }

        return true;
    }
}
