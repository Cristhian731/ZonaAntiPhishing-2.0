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
