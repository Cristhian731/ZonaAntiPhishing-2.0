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
            '0.95 0.97 0.99 rg 0 0 842 595 re f',
            '0.98 0.985 0.99 rg 20 20 802 555 re f',
            '0.75 0.60 0.30 RG 1.8 w 34 34 774 527 re S',
            '0.84 0.88 0.91 RG 0.7 w 44 44 754 507 re S',
            'Q',
            '0.75 0.60 0.30 rg 386 491 70 1 re f',
            '0.75 0.60 0.30 rg 150 105 542 0.8 re f',
        ];

        $navy = '0.05 0.16 0.25';
        $muted = '0.38 0.43 0.47';
        $gold = '0.75 0.60 0.30';
        $issuedTimestamp = strtotime((string) ($certificate['issued_at'] ?? ''));
        $issuedDate = $issuedTimestamp !== false
            ? date('F j, Y', $issuedTimestamp)
            : (string) ($certificate['issued_at'] ?? '');
        $this->addCenteredText($commands, 'ZA', 257, 190, 'F2', '0.94 0.95 0.97');
        $this->addCenteredText($commands, 'Zona AntiPhishing', 535, 18, 'F2', $navy);
        $this->addCenteredText($commands, 'Cybersecurity Awareness Platform', 516, 11, 'F1', $muted);
        $this->addCenteredText($commands, 'CERTIFICATE OF COMPLETION', 473, 25, 'F2', $navy);
        $this->addCenteredText($commands, 'This certificate is proudly awarded to', 438, 13, 'F1', $muted);
        $this->addCenteredWrappedText($commands, (string) ($certificate['user_name'] ?? ''), 398, 25, 'F2', $navy, 700, 29);
        $this->addCenteredText($commands, 'for successfully completing the course', 359, 13, 'F1', $muted);
        $this->addCenteredWrappedText($commands, (string) ($certificate['course_title'] ?? ''), 329, 21, 'F2', $navy, 700, 23);
        $this->addCenteredText($commands, 'and demonstrating knowledge in phishing awareness, prevention', 275, 12, 'F1', $muted);
        $this->addCenteredText($commands, 'and digital security best practices.', 257, 12, 'F1', $muted);

        $commands[] = '0.75 0.60 0.30 RG 1.2 w 384 207 m 403 224 l 439 224 l 458 207 l 458 186 l 439 169 l 403 169 l 384 186 l h S';
        $this->addCenteredText($commands, 'VERIFIED', 198, 9, 'F2', $gold);
        $this->addCenteredText($commands, 'COMPLETION', 185, 8, 'F2', $gold);

        $this->addCenteredText($commands, 'Issue Date: ' . $issuedDate, 143, 12, 'F1', $navy);
        $this->addCenteredText($commands, 'Certificate ID: ' . (string) ($certificate['certificate_code'] ?? ''), 124, 12, 'F1', $navy);
        $commands[] = '0.38 0.43 0.47 RG 0.7 w 321 91 m 521 91 l S';
        $this->addCenteredText($commands, 'Zona AntiPhishing Team', 72, 12, 'F2', $navy);
        $this->addCenteredText($commands, 'Educational Security Program', 55, 10, 'F1', $muted);

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

    private function addCenteredWrappedText(
        array &$commands,
        string $text,
        int $y,
        int $fontSize,
        string $font,
        string $color,
        int $maxWidth,
        int $lineHeight
    ): void {
        $encodedText = function_exists('iconv')
            ? @iconv('UTF-8', 'Windows-1252//TRANSLIT', $text)
            : false;

        if ($encodedText === false) {
            $encodedText = preg_replace('/[^\x20-\x7E]/', '?', $text) ?? '';
        }

        $encodedText = preg_replace('/[\x00-\x1F\x7F]/', ' ', $encodedText) ?? '';
        $maxCharacters = max(1, (int) floor($maxWidth / ($fontSize * 0.54)));
        $wrappedText = wordwrap(trim($encodedText), $maxCharacters, "\n", true);
        $lines = preg_split('/\n/', $wrappedText) ?: [];

        $firstLineY = $y + (int) (floor((count($lines) - 1) / 2) * $lineHeight);

        foreach ($lines as $index => $wrappedLine) {
            $lineY = $firstLineY - ($index * $lineHeight);
            $this->addCenteredText($commands, $wrappedLine, $lineY, $fontSize, $font, $color);
        }
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
        $fontSize = min($fontSize, max(8, (int) floor(700 / max(1, strlen($encodedText) * 0.54))));
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