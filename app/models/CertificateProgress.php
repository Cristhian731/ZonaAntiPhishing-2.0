<?php

require_once __DIR__ . '/../../config/database.php';

class CertificateProgress
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function isCourseCompleted(int $userId, int $courseId): bool
    {
        if ($userId < 1 || $courseId < 1) {
            return false;
        }

        $totalSql = "SELECT COUNT(*)
                     FROM lessons
                     WHERE course_id = :course_id";

        $totalStmt = $this->conn->prepare($totalSql);
        $totalStmt->execute([
            'course_id' => $courseId,
        ]);
        $totalLessons = (int) $totalStmt->fetchColumn();

        if ($totalLessons === 0) {
            return false;
        }

        $completedSql = "SELECT COUNT(DISTINCT lessons.id)
                         FROM lessons
                         INNER JOIN user_progress
                             ON user_progress.lesson_id = lessons.id
                             AND user_progress.user_id = :user_id
                             AND user_progress.completed = 1
                         WHERE lessons.course_id = :course_id";

        $completedStmt = $this->conn->prepare($completedSql);
        $completedStmt->execute([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);
        $completedLessons = (int) $completedStmt->fetchColumn();

        return $completedLessons === $totalLessons;
    }

    public function certificateExists(int $userId, int $courseId): bool
    {
        $sql = "SELECT id
                FROM certificates
                WHERE user_id = :user_id AND course_id = :course_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function generateCertificate(int $userId, int $courseId): bool
    {
        if ($userId < 1 || $courseId < 1) {
            return false;
        }

        $this->conn->beginTransaction();

        try {
            $courseSql = "SELECT id, title
                          FROM courses
                          WHERE id = :course_id
                          LIMIT 1
                          FOR UPDATE";

            $courseStmt = $this->conn->prepare($courseSql);
            $courseStmt->execute([
                'course_id' => $courseId,
            ]);
            $course = $courseStmt->fetch(PDO::FETCH_ASSOC);

            if (!$course || $this->certificateExists($userId, $courseId)) {
                $this->conn->rollBack();
                return false;
            }

            if (!$this->isCourseCompleted($userId, $courseId)) {
                $this->conn->rollBack();
                return false;
            }

            $insertSql = "INSERT INTO certificates
                              (user_id, course_id, certificate_code, title, issued_at)
                          VALUES
                              (:user_id, :course_id, :certificate_code, :title, CURRENT_TIMESTAMP)";

            $insertStmt = $this->conn->prepare($insertSql);
            $certificateTitle = 'Certificate of Completion: ' . (string) $course['title'];

            for ($attempt = 0; $attempt < 5; $attempt++) {
                $certificateCode = 'ZAP-' . strtoupper(bin2hex(random_bytes(3)));

                try {
                    $insertStmt->execute([
                        'user_id' => $userId,
                        'course_id' => $courseId,
                        'certificate_code' => $certificateCode,
                        'title' => $certificateTitle,
                    ]);
                } catch (PDOException $exception) {
                    if (($exception->errorInfo[1] ?? null) === 1062 && $attempt < 4) {
                        continue;
                    }

                    throw $exception;
                }

                $this->conn->commit();
                return true;
            }

            $this->conn->rollBack();
            return false;
        } catch (Throwable $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            throw $exception;
        }
    }
}
