<?php

require_once __DIR__ . '/CertificateController.php';

class CertificatePdfController
{
    public function download(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ?page=login');
            exit;
        }

        $certificateId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if ($certificateId === false || $certificateId === null || $certificateId < 1) {
            http_response_code(404);
            exit('Certificate not found.');
        }

        $certificateController = new CertificateController();
        $certificate = $certificateController->getCertificate($certificateId);

        if (
            $certificate === null
            || (int) $certificate['user_id'] !== (int) $_SESSION['user_id']
        ) {
            http_response_code(404);
            exit('Certificate not found.');
        }

        $pdf = $this->createCertificatePdf($certificate);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="certificate-' . $certificateId . '.pdf"');
        header('Content-Length: ' . strlen($pdf));
        header('Cache-Control: private, no-store, max-age=0');

        echo $pdf;
    }

    private function createCertificatePdf(array $certificate): string
    {
        $commands = [
            'q',
            '0.05 0.16 0.25 rg 0 0 842 595 re f',
            '1 1 1 rg 20 20 802 555 re f',
            '0.75 0.60 0.30 RG 2 w 34 34 774 527 re S',
            '0.88 0.84 0.74 RG 0.7 w 44 44 754 507 re S',
            'Q',
            '0.05 0.16 0.25 rg 271 480 300 1.5 re f',
        ];

        $this->addCenteredText($commands, 'Zona AntiPhishing', 511, 18, 'F2', '0.05 0.16 0.25');
        $this->addCenteredText($commands, 'Certificate of Completion', 432, 30, 'F2', '0.05 0.16 0.25');
        $this->addCenteredText($commands, 'Awarded To:', 379, 15, 'F1', '0.38 0.43 0.47');
        $this->addCenteredText($commands, (string) ($certificate['user_name'] ?? ''), 339, 29, 'F2', '0.05 0.16 0.25');
        $this->addCenteredText($commands, 'Course:', 291, 15, 'F1', '0.38 0.43 0.47');
        $this->addCenteredText($commands, (string) ($certificate['course_title'] ?? ''), 253, 23, 'F2', '0.05 0.16 0.25');
        $this->addCenteredText($commands, 'Certificate Code: ' . (string) ($certificate['certificate_code'] ?? ''), 154, 14, 'F1', '0.05 0.16 0.25');
        $this->addCenteredText($commands, 'Issued Date: ' . (string) ($certificate['issued_at'] ?? ''), 119, 14, 'F1', '0.05 0.16 0.25');

        $stream = implode("\n", $commands) . "\n";
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            3 => '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            5 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
            6 => "<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . 'endstream',
        ];

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0];

        foreach ($objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 7\n0000000000 65535 f \n";

        for ($number = 1; $number <= 6; $number++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$number]);
        }

        $pdf .= "trailer\n<< /Size 7 /Root 1 0 R >>\nstartxref\n";
        $pdf .= $xrefOffset . "\n%%EOF\n";

        return $pdf;
    }

    private function addCenteredText(
        array &$commands,
        string $text,
        int $y,
        int $fontSize,
        string $font,
        string $color
    ): void {
        $encodedText = function_exists('iconv')
            ? @iconv('UTF-8', 'Windows-1252//TRANSLIT', $text)
            : false;

        if ($encodedText === false) {
            $encodedText = preg_replace('/[^\x20-\x7E]/', '?', $text) ?? '';
        }

        $encodedText = str_replace(["\\", '(', ')'], ["\\\\", '\\(', '\\)'], $encodedText);
        $encodedText = preg_replace('/[\x00-\x1F\x7F]/', ' ', $encodedText) ?? '';
        $fontSize = min($fontSize, max(12, (int) floor(700 / max(1, strlen($encodedText) * 0.54))));
        $estimatedWidth = strlen($encodedText) * $fontSize * 0.54;
        $x = max(55, (842 - $estimatedWidth) / 2);

        $commands[] = sprintf(
            'BT /%s %d Tf %s rg 1 0 0 1 %.2f %d Tm (%s) Tj ET',
            $font,
            $fontSize,
            $color,
            $x,
            $y,
            $encodedText
        );
    }
}