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
                 WHERE user_id = :progress_user_id
                 AND completed = 1) AS lessons_completed,

                (SELECT COUNT(*) FROM quiz_attempts
                 WHERE user_id = :attempt_user_id
                 AND passed = 1) AS quizzes_passed,

                (SELECT COUNT(*) FROM simulation_attempts
                 WHERE user_id = :simulation_user_id
                 AND passed = 1) AS simulations_passed,

                (SELECT COUNT(*) FROM lessons) AS total_lessons";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'progress_user_id' => $userId,
            'attempt_user_id' => $userId,
            'simulation_user_id' => $userId,
        ]);

        $metrics = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($metrics) ? $metrics : [];
    }

    public function getLearningHubForUser(int $userId): array
    {
        if ($userId < 1) {
            return ['state' => 'new'];
        }

        $totalsSql = "SELECT
                          COUNT(DISTINCT lessons.id) AS total_lessons,
                          COUNT(DISTINCT CASE
                              WHEN user_progress.completed = 1 THEN lessons.id
                          END) AS completed_lessons
                       FROM lessons
                       LEFT JOIN user_progress
                           ON user_progress.lesson_id = lessons.id
                          AND user_progress.user_id = :user_id";

        $totalsStmt = $this->conn->prepare($totalsSql);
        $totalsStmt->execute(['user_id' => $userId]);
        $totals = $totalsStmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $totalLessons = (int) ($totals['total_lessons'] ?? 0);
        $completedLessons = (int) ($totals['completed_lessons'] ?? 0);

        if ($totalLessons > 0 && $completedLessons >= $totalLessons) {
            return [
                'state' => 'completed',
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
            ];
        }

        $courseSql = "SELECT
                          courses.id AS course_id,
                          courses.title AS course_title,
                          COUNT(DISTINCT lessons.id) AS course_total,
                          COUNT(DISTINCT CASE
                              WHEN user_progress.completed = 1 THEN lessons.id
                          END) AS course_completed,
                          MAX(CASE
                              WHEN user_progress.completed = 1 THEN user_progress.completed_at
                          END) AS last_activity
                      FROM courses
                      INNER JOIN lessons ON lessons.course_id = courses.id
                      LEFT JOIN user_progress
                          ON user_progress.lesson_id = lessons.id
                         AND user_progress.user_id = :user_id
                      GROUP BY courses.id, courses.title
                      HAVING COUNT(DISTINCT CASE
                          WHEN user_progress.completed = 1 THEN lessons.id
                      END) < COUNT(DISTINCT lessons.id)
                      ORDER BY (MAX(CASE
                          WHEN user_progress.completed = 1 THEN user_progress.completed_at
                      END) IS NULL) ASC,
                      MAX(CASE
                          WHEN user_progress.completed = 1 THEN user_progress.completed_at
                      END) DESC,
                      courses.id ASC
                      LIMIT 1";

        $courseStmt = $this->conn->prepare($courseSql);
        $courseStmt->execute(['user_id' => $userId]);
        $course = $courseStmt->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            return [
                'state' => 'new',
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
            ];
        }

        $lessonSql = "SELECT lessons.id AS lesson_id, lessons.title AS lesson_title
                      FROM lessons
                      WHERE lessons.course_id = :course_id
                        AND NOT EXISTS (
                            SELECT 1
                            FROM user_progress
                            WHERE user_progress.lesson_id = lessons.id
                              AND user_progress.user_id = :user_id
                              AND user_progress.completed = 1
                        )
                      ORDER BY lessons.lesson_order ASC, lessons.id ASC
                      LIMIT 1";

        $lessonStmt = $this->conn->prepare($lessonSql);
        $lessonStmt->execute([
            'course_id' => (int) $course['course_id'],
            'user_id' => $userId,
        ]);
        $lesson = $lessonStmt->fetch(PDO::FETCH_ASSOC);

        if (!$lesson) {
            return [
                'state' => 'new',
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
            ];
        }

        $courseTotal = (int) ($course['course_total'] ?? 0);
        $courseCompleted = (int) ($course['course_completed'] ?? 0);

        return [
            'state' => $completedLessons === 0 ? 'new' : 'active',
            'course_id' => (int) $course['course_id'],
            'course_title' => (string) $course['course_title'],
            'lesson_id' => (int) $lesson['lesson_id'],
            'lesson_title' => (string) $lesson['lesson_title'],
            'course_completed' => $courseCompleted,
            'course_total' => $courseTotal,
            'course_progress_percent' => $courseTotal > 0
                ? (int) round(($courseCompleted / $courseTotal) * 100)
                : 0,
            'completed_lessons' => $completedLessons,
            'total_lessons' => $totalLessons,
        ];
    }
}