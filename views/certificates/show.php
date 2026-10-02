<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$certificateId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($certificateId === false || $certificateId === null || $certificateId < 1) {
    header('Location: ?page=certificates');
    exit;
}

require_once __DIR__ . '/../../app/controllers/CertificateController.php';

$certificateController = new CertificateController();
$certificate = $certificateController->getCertificate($certificateId);

if ($certificate === null || (int) $certificate['user_id'] !== (int) $_SESSION['user_id']) {
    header('Location: ?page=certificates');
    exit;
}

$title = 'Certificate | Zona AntiPhishing';
$activePage = 'certificates';

ob_start();
?>

<div class="mb-4">
    <a class="btn btn-outline-secondary" href="?page=certificates">&larr; Certificates</a>
    <a class="btn btn-primary ms-2" href="?page=certificate-pdf&amp;id=<?= (int) $certificate['id']; ?>">
        Download PDF
    </a>
</div>

<article class="card border-primary shadow-sm mx-auto" style="max-width: 760px;">
    <div class="card-body text-center p-4 p-md-5">
        <p class="small fw-bold text-primary text-uppercase mb-2">Zona AntiPhishing</p>
        <h1 class="display-6 fw-bold mb-4">Certificate of Completion</h1>

        <p class="text-body-secondary mb-1">Awarded To</p>
        <p class="h3 fw-semibold mb-4">
            <?= htmlspecialchars((string) $certificate['user_name'], ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <p class="text-body-secondary mb-1">Course</p>
        <p class="h4 fw-semibold mb-4">
            <?= htmlspecialchars((string) $certificate['course_title'], ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <dl class="row text-start border-top pt-4 mb-0">
            <dt class="col-sm-4 text-body-secondary">Certificate Code</dt>
            <dd class="col-sm-8 fw-semibold">
                <?= htmlspecialchars((string) $certificate['certificate_code'], ENT_QUOTES, 'UTF-8'); ?>
            </dd>

            <dt class="col-sm-4 text-body-secondary">Issued Date</dt>
            <dd class="col-sm-8 mb-0">
                <?= htmlspecialchars((string) $certificate['issued_at'], ENT_QUOTES, 'UTF-8'); ?>
            </dd>
        </dl>
    </div>
</article>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';