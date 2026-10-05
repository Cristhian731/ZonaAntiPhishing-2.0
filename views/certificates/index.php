<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

require_once __DIR__ . '/../../app/controllers/CertificateController.php';

$certificateController = new CertificateController();

$certificates = $certificateController->getUserCertificates(
    (int) $_SESSION['user_id']
);

$title = 'Certificates | Zona AntiPhishing';
$activePage = 'certificates';

ob_start();

?>

<header class="mb-4 mb-lg-5">

    <p class="small fw-bold text-primary text-uppercase mb-2">
        Achievements
    </p>

    <h1 class="display-6 fw-bold mb-2">
        My Certificates
    </h1>

    <p class="text-body-secondary mb-0">
        Certificates earned through your learning journey.
    </p>

</header>

<?php if (empty($certificates)): ?>

    <div class="alert alert-light border">
        No certificates available yet.
    </div>

<?php else: ?>

    <div class="row row-cols-1 row-cols-lg-2 g-3">

        <?php foreach ($certificates as $certificate): ?>

            <div class="col">

                <div class="card dashboard-stat achievement-card shadow-sm">

                    <div class="card-body">

                        <h3 class="h5 fw-bold">
                            <?= htmlspecialchars((string) ($certificate['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </h3>

                        <p>
                            <strong>Course:</strong>
                            <?= htmlspecialchars((string) ($certificate['course_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </p>

                        <p>
                            <strong>Certificate Code:</strong>
                            <?= htmlspecialchars((string) ($certificate['certificate_code'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </p>

                        <p>
                            <strong>Issued Date:</strong>
                            <?= htmlspecialchars((string) ($certificate['issued_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </p>

                        <a class="btn btn-achievement" href="?page=certificate&amp;id=<?= (int) $certificate['id']; ?>">
                            View Certificate
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';