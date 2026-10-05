<?php

require_once __DIR__ . '/../../config/database.php';

class Course
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllCourses(int $userId = 0): array
    {
        $sql = "SELECT courses.*,
                       (SELECT COUNT(*)
                        FROM lessons
                        WHERE lessons.course_id = courses.id) AS lesson_count,
                       (SELECT COUNT(*)
                        FROM lessons
                        INNER JOIN user_progress
                            ON user_progress.lesson_id = lessons.id
                            AND user_progress.user_id = :user_id
                            AND user_progress.completed = 1
                        WHERE lessons.course_id = courses.id) AS completed_lessons
                FROM courses
                ORDER BY courses.id ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById(int $id)
    {
        $sql = "SELECT * FROM courses WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
