<?php

require_once __DIR__ . '/../models/Certificate.php';

class CertificateController
{
    private Certificate $certificateModel;

    public function __construct()
    {
        $this->certificateModel = new Certificate();
    }

    public function getUserCertificates(int $userId): array
    {
        return $this->certificateModel->getCertificatesByUserId($userId);
    }

    public function getCertificate(int $id): ?array
    {
        return $this->certificateModel->getCertificateById($id);
    }
}