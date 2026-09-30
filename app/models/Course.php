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

    public function getAllCourses()
    {
        $sql = "SELECT * FROM courses";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

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
