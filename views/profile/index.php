<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$title = 'Profile | Zona AntiPhishing';
$activePage = 'profile';
$userName = htmlspecialchars((string) ($_SESSION['user_name'] ?? ''), ENT_QUOTES, 'UTF-8');
$userRole = htmlspecialchars((string) ($_SESSION['user_role'] ?? ''), ENT_QUOTES, 'UTF-8');

require_once __DIR__ . '/../../app/controllers/DashboardController.php';

$dashboardController = new DashboardController();
$metrics = $dashboardController->getMetricsForUser((int) $_SESSION['user_id']);

$profileMetrics = [
    [
        'label' => 'Lessons Completed',
        'value' => (string) $metrics['lessons_completed'],
        'style' => '',
    ],
    [
        'label' => 'Quizzes Passed',
        'value' => (string) $metrics['quizzes_passed'],
        'style' => 'stat-deep',
    ],
    [
        'label' => 'Simulations Passed',
        'value' => (string) $metrics['simulations_passed'],
        'style' => 'stat-black',
    ],
    [
        'label' => 'Progress %',
        'value' => (string) $metrics['progress_percent'] . '%',
        'style' => 'stat-deep',
    ],
];

ob_start();
?>

<header class="mb-4 mb-lg-5">
    <p class="small fw-bold text-primary text-uppercase mb-2">Account</p>
    <h1 class="display-6 fw-bold mb-2">My Profile</h1>
    <p class="text-body-secondary mb-0">Your account details and learning progress.</p>
</header>

<section class="card profile-panel border-0 shadow-sm mb-4" aria-labelledby="profile-details-title">
    <div class="card-body p-4">
        <h2 id="profile-details-title" class="h5 fw-bold mb-4">Profile details</h2>

        <dl class="row mb-0">
            <dt class="col-sm-3 text-body-secondary">Name</dt>
            <dd class="col-sm-9 fw-semibold"><?= $userName !== '' ? $userName : 'User'; ?></dd>

            <dt class="col-sm-3 text-body-secondary">Role</dt>
            <dd class="col-sm-9 mb-0"><?= $userRole !== '' ? $userRole : 'User'; ?></dd>
        </dl>
    </div>
</section>

<section aria-labelledby="profile-progress-title">
    <div class="mb-3">
        <h2 id="profile-progress-title" class="h5 fw-bold mb-1">Learning progress</h2>
        <p class="small text-body-secondary mb-0">Your activity across the platform.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 g-lg-4">
        <?php foreach ($profileMetrics as $metric): ?>
            <div class="col">
                <article
                    class="card dashboard-stat <?= htmlspecialchars($metric['style'], ENT_QUOTES, 'UTF-8'); ?> shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6 text-body-secondary fw-semibold mb-3">
                            <?= htmlspecialchars($metric['label'], ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <p class="dashboard-stat-value mb-0">
                            <?= htmlspecialchars($metric['value'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<div class="mt-4">
    <a class="btn btn-danger" href="?page=logout">Logout</a>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';