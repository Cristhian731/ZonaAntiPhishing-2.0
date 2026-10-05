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

require_once __DIR__ . '/../../app/controllers/DashboardController.php';

$dashboardController = new DashboardController();
$dashboardMetrics = $dashboardController->getMetricsForUser((int) $_SESSION['user_id']);
$learningHub = $dashboardController->getLearningHubForUser((int) $_SESSION['user_id']);
$learningHubState = (string) ($learningHub['state'] ?? 'new');
$hasLearningTarget = (int) ($learningHub['lesson_id'] ?? 0) > 0;
$hubCourseTitle = htmlspecialchars((string) ($learningHub['course_title'] ?? ''), ENT_QUOTES, 'UTF-8');
$hubLessonTitle = htmlspecialchars((string) ($learningHub['lesson_title'] ?? ''), ENT_QUOTES, 'UTF-8');
$hubCourseCompleted = (int) ($learningHub['course_completed'] ?? 0);
$hubCourseTotal = (int) ($learningHub['course_total'] ?? 0);
$hubCourseProgress = (int) ($learningHub['course_progress_percent'] ?? 0);

$metrics = [
    [
        'id' => 'courses',
        'label' => 'Courses Available',
        'value' => (string) $dashboardMetrics['courses_available'],
        'style' => '',
    ],
    [
        'id' => 'lessons-completed',
        'label' => 'Lessons Completed',
        'value' => (string) $dashboardMetrics['lessons_completed'],
        'style' => 'stat-deep',
    ],
    [
        'id' => 'quizzes-passed',
        'label' => 'Quizzes Passed',
        'value' => (string) $dashboardMetrics['quizzes_passed'],
        'style' => 'stat-black',
    ],
    [
        'id' => 'simulations-passed',
        'label' => 'Simulations Passed',
        'value' => (string) $dashboardMetrics['simulations_passed'],
        'style' => 'stat-deep',
    ],
    [
        'id' => 'progress',
        'label' => 'Progress %',
        'value' => (string) $dashboardMetrics['progress_percent'] . '%',
        'style' => '',
    ],
];

ob_start();
?>

<style>
    .learning-hub-card {
        border-top-width: 4px;
        border-top-color: var(--zap-primary);
        background: var(--zap-deep);
        box-shadow: 0 0.5rem 1.25rem rgb(18 58 55 / 15%) !important;
        color: #fff;
    }

    .learning-hub-card .card-body {
        min-height: 0;
        padding: 1.4rem 1.5rem !important;
    }

    .learning-hub-card h2,
    .learning-hub-card > .card-body > .learning-hub-copy > p:not(.text-body-secondary) {
        color: #fff;
    }

    .learning-hub-card .learning-hub-copy > p.text-body-secondary {
        color: rgb(255 255 255 / 82%) !important;
    }

    .learning-hub-card .learning-hub-copy > p:first-child {
        color: rgb(255 255 255 / 76%) !important;
    }

    .learning-hub-copy {
        min-width: 0;
        max-width: 58rem;
    }

    .learning-hub-details {
        padding: 0;
        color: #fff;
    }

    .learning-hub-details .text-body-secondary {
        color: rgb(255 255 255 / 74%) !important;
    }

    .learning-hub-details .progress {
        background: rgb(255 255 255 / 22%);
    }

    .learning-hub-details .progress-bar {
        background: #8BC3A9;
    }

    .learning-hub-progress {
        width: 100%;
        max-width: 38rem;
    }

    .learning-hub-action {
        min-width: 14rem;
        padding: 0;
    }

    .learning-hub-action > p {
        color: rgb(255 255 255 / 76%) !important;
    }

    .learning-hub-cta {
        min-width: 13rem;
        box-shadow: none;
    }

    @media (max-width: 991.98px) {
        .learning-hub-action {
            width: 100%;
        }

        .learning-hub-cta {
            width: 100%;
        }
    }
</style>

<header id="dashboard-top"
    class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4 mb-lg-5">
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

<section class="card dashboard-stat learning-hub-card shadow-sm mb-4 mb-lg-5" aria-labelledby="learning-hub-title">
    <div
        class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 p-4 p-lg-5">
        <div class="learning-hub-copy flex-grow-1">
            <?php if ($learningHubState === 'completed'): ?>
                <p class="small fw-bold text-primary text-uppercase mb-2">Learning journey</p>
                <h2 id="learning-hub-title" class="h3 fw-bold mb-2">Congratulations!</h2>
                <p class="text-body-secondary mb-3">
                    You completed all available cybersecurity training.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-achievement btn-lg learning-hub-cta" href="?page=certificates">View Certificates</a>
                    <a class="btn btn-outline-light btn-lg" href="?page=courses">Explore Courses</a>
                </div>
            <?php else: ?>
                <p class="small fw-bold text-primary text-uppercase mb-2">
                    <?= $learningHubState === 'active' ? 'Continue Learning' : 'Start Learning'; ?>
                </p>
                <h2 id="learning-hub-title" class="h3 fw-bold mb-2">
                    <?= $learningHubState === 'active' ? 'Pick up where you left off' : 'Your training starts here'; ?>
                </h2>
                <?php if ($learningHubState === 'new'): ?>
                    <p class="text-body-secondary mb-3">
                        You haven&apos;t started your cybersecurity training yet.
                    </p>
                <?php endif; ?>

                <?php if ($hasLearningTarget): ?>
                    <div class="learning-hub-details mb-3">
                        <p class="small mb-1">
                            <span class="text-body-secondary">Course</span>
                            <strong class="ms-1"><?= $hubCourseTitle; ?></strong>
                        </p>
                        <p class="mb-3">
                            <span class="text-body-secondary">
                                <?= $learningHubState === 'active' ? 'Next lesson' : 'First lesson'; ?>
                            </span>
                            <strong class="ms-1"><?= $hubLessonTitle; ?></strong>
                        </p>

                        <div class="learning-hub-progress" aria-label="Course progress">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                <span class="small fw-semibold">
                                    <?= $hubCourseCompleted; ?> of <?= $hubCourseTotal; ?> lessons completed
                                </span>
                                <span class="small text-body-secondary"><?= $hubCourseProgress; ?>%</span>
                            </div>
                            <div class="progress" role="progressbar" aria-label="Progress in <?= $hubCourseTitle; ?>"
                                aria-valuenow="<?= $hubCourseProgress; ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: <?= $hubCourseProgress; ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-body-secondary mb-3">
                        You haven't started your cybersecurity training yet. Explore the course library to begin.
                    </p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if ($learningHubState !== 'completed'): ?>
            <?php if ($hasLearningTarget): ?>
                <div class="learning-hub-action flex-shrink-0">
                    <p class="small fw-bold text-primary text-uppercase mb-2">Your next step</p>
                    <a class="btn btn-primary btn-lg learning-hub-cta"
                        href="?page=lesson&amp;id=<?= (int) $learningHub['lesson_id']; ?>">
                        <?= $learningHubState === 'active' ? 'Continue Learning' : 'Start Learning'; ?>
                        <span class="ms-2" aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="learning-hub-action flex-shrink-0">
                    <p class="small fw-bold text-primary text-uppercase mb-2">Your next step</p>
                    <a class="btn btn-primary btn-lg learning-hub-cta" href="?page=courses">
                        Explore Courses <span class="ms-2" aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<section class="mb-4 mb-lg-5" aria-labelledby="quick-actions-title">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-2 mb-3">
        <div>
            <h2 id="quick-actions-title" class="h5 fw-bold mb-1">Quick Actions</h2>
            <p class="small text-body-secondary mb-0">Practice and check a suspicious link.</p>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
            <a class="quick-action-card" href="?page=simulations">
                <div class="d-flex align-items-center gap-3">
                    <span class="quick-action-icon quick-action-icon-shield" aria-hidden="true"></span>
                    <span class="quick-action-label">Practice</span>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-2">Practice Simulations</h3>
                    <p class="text-body-secondary mb-3">Build phishing recognition skills with realistic situations.</p>
                    <span class="quick-action-link">Open simulations <span
                            aria-hidden="true">&rarr;</span></span>
                </div>
            </a>
        </div>
        <div class="col">
            <a class="quick-action-card" href="?page=url-analyzer">
                <div class="d-flex align-items-center gap-3">
                    <span class="quick-action-icon quick-action-icon-search" aria-hidden="true"></span>
                    <span class="quick-action-label">Analyze</span>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-2">URL Analyzer</h3>
                    <p class="text-body-secondary mb-3">Review warning signs in a suspicious link before opening it.</p>
                    <span class="quick-action-link">Analyze a URL <span aria-hidden="true">&rarr;</span></span>
                </div>
            </a>
        </div>
    </div>
</section>

<section aria-labelledby="learning-stats-title">
    <div class="mb-3">
        <h2 id="learning-stats-title" class="h5 fw-bold mb-1">Learning progress</h2>
        <p class="small text-body-secondary mb-0">Your activity at a glance</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-5 g-3 g-lg-4">
        <?php foreach ($metrics as $metric): ?>
            <div class="col" id="<?= htmlspecialchars($metric['id'], ENT_QUOTES, 'UTF-8'); ?>">
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

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';