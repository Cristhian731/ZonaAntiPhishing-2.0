<?php

require_once __DIR__ . '/../../config/database.php';

class Lesson
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getLessonsByCourseId(int $courseId)
    {
        $sql = "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY lesson_order ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLessonById(int $id)
    {
        $sql = "SELECT * FROM lessons WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
