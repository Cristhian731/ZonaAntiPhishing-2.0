<?php

require_once __DIR__ . '/../../config/database.php';

class Quiz
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getQuizzesForUser(int $userId): array
    {
        $sql = "SELECT quizzes.id AS quiz_id,
                       quizzes.title AS quiz_title,
                       courses.title AS course_title,
                       lessons.id AS lesson_id,
                       CASE
                           WHEN EXISTS (
                               SELECT 1
                               FROM quiz_attempts AS passed_attempts
                               WHERE passed_attempts.quiz_id = quizzes.id
                                 AND passed_attempts.user_id = :passed_user_id
                                 AND passed_attempts.passed = 1
                           ) THEN 'passed'
                           WHEN EXISTS (
                               SELECT 1
                               FROM quiz_attempts AS any_attempts
                               WHERE any_attempts.quiz_id = quizzes.id
                                 AND any_attempts.user_id = :attempt_user_id
                           ) THEN 'failed'
                           ELSE 'not_attempted'
                       END AS status
                FROM quizzes
                LEFT JOIN lessons ON lessons.id = quizzes.lesson_id
                LEFT JOIN courses ON courses.id = lessons.course_id
                ORDER BY courses.title ASC, lessons.lesson_order ASC, quizzes.title ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'passed_user_id' => $userId,
            'attempt_user_id' => $userId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuizByLessonId(int $lessonId)
    {
        $sql = "SELECT * FROM quizzes WHERE lesson_id = :lesson_id ORDER BY id ASC LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':lesson_id', $lessonId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getQuestionsByQuizId(int $quizId)
    {
        $sql = "SELECT * FROM quiz_questions WHERE quiz_id = :quiz_id ORDER BY id ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':quiz_id', $quizId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOptionsByQuestionId(int $questionId)
    {
        $sql = "SELECT * FROM quiz_options WHERE question_id = :question_id ORDER BY id ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':question_id', $questionId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveQuizAttempt(int $userId, int $quizId, int $score, bool $passed): bool
    {
        $sql = "INSERT INTO quiz_attempts (user_id, quiz_id, score, passed, completed_at)
                VALUES (:user_id, :quiz_id, :score, :passed, CURRENT_TIMESTAMP)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'score' => $score,
            'passed' => (int) $passed,
        ]);
    }
}
