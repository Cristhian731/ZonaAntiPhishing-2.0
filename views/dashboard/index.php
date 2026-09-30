<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$title = 'Dashboard | Zona AntiPhishing';
$userName = trim((string) ($_SESSION['user_name'] ?? ''));
$userRole = trim((string) ($_SESSION['user_role'] ?? ''));
$safeUserName = htmlspecialchars($userName, ENT_QUOTES, 'UTF-8');
$safeUserRole = htmlspecialchars($userRole, ENT_QUOTES, 'UTF-8');

$metrics = [
    [
        'id' => 'courses',
        'label' => 'Courses Available',
        'value' => '—',
        'style' => '',
    ],
    [
        'id' => 'quizzes',
        'label' => 'Quizzes Completed',
        'value' => '—',
        'style' => 'stat-deep',
    ],
    [
        'id' => 'simulations',
        'label' => 'Simulations Completed',
        'value' => '—',
        'style' => 'stat-black',
    ],
    [
        'id' => 'certificates',
        'label' => 'Certificates',
        'value' => '—',
        'style' => '',
    ],
];

ob_start();
?>

<header id="dashboard-top" class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4 mb-lg-5">
    <div>
        <p class="small fw-bold text-primary text-uppercase mb-2">Learning overview</p>
        <h1 class="display-6 fw-bold mb-2">Your dashboard</h1>
        <p class="text-body-secondary mb-0">Track your progress in phishing awareness training.</p>
    </div>

    <section id="profile" class="profile-panel bg-white rounded-2 shadow-sm px-3 py-2" aria-label="Profile summary">
        <p class="small text-body-secondary mb-1">Signed in as</p>
        <p class="fw-semibold mb-0"><?= $safeUserName !== '' ? $safeUserName : 'User'; ?></p>
        <?php if ($safeUserRole !== ''): ?>
            <p class="small text-body-secondary mb-0"><?= $safeUserRole; ?></p>
        <?php endif; ?>
    </section>
</header>

<section aria-labelledby="learning-stats-title">
    <div class="mb-3">
        <h2 id="learning-stats-title" class="h5 fw-bold mb-1">Learning progress</h2>
        <p class="small text-body-secondary mb-0">Your activity at a glance</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 g-lg-4">
        <?php foreach ($metrics as $metric): ?>
            <div class="col" id="<?= htmlspecialchars($metric['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <article class="card dashboard-stat <?= htmlspecialchars($metric['style'], ENT_QUOTES, 'UTF-8'); ?> shadow-sm">
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

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';