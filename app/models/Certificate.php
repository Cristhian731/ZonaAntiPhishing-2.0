<?php

require_once __DIR__ . '/../../config/database.php';

class Certificate
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getCertificatesByUserId(int $userId): array
    {
        $sql = "SELECT certificates.id,
                       certificates.title,
                       certificates.certificate_code,
                       certificates.issued_at,
                       certificates.course_id,
                       courses.title AS course_title
                FROM certificates
                INNER JOIN courses ON courses.id = certificates.course_id
                WHERE user_id = :user_id
                ORDER BY certificates.issued_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCertificateById(int $id): ?array
    {
        $sql = "SELECT certificates.id,
                       certificates.user_id,
                       certificates.title,
                       certificates.certificate_code,
                       certificates.issued_at,
                       courses.title AS course_title,
                       users.name AS user_name
                FROM certificates
                INNER JOIN courses ON courses.id = certificates.course_id
                INNER JOIN users ON users.id = certificates.user_id
                WHERE certificates.id = :id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);

        $certificate = $stmt->fetch(PDO::FETCH_ASSOC);

        return $certificate !== false ? $certificate : null;
    }
}