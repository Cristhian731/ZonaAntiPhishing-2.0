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
$learningHub = $dashboardController->getLearningHubForUser((int) $_SESSION['user_id']);
$learningState = (string) ($learningHub['state'] ?? 'new');
$completedLessons = (int) ($learningHub['completed_lessons'] ?? $metrics['lessons_completed'] ?? 0);
$progressPercent = max(0, min(100, (int) ($metrics['progress_percent'] ?? 0)));
$nextLessonId = (int) ($learningHub['lesson_id'] ?? 0);

ob_start();
?>

<style>
    .profile-learning-hero {
        position: relative;
        overflow: hidden;
        padding: clamp(1.75rem, 5vw, 3rem);
        border: 1px solid rgb(255 255 255 / 10%);
        border-radius: calc(var(--zap-radius) * 1.5);
        background: var(--zap-deep);
        color: #fff;
        box-shadow: 0 1rem 2.25rem rgb(18 58 55 / 16%);
    }

    .profile-learning-hero h1,
    .profile-learning-hero p {
        color: #fff;
    }

    .profile-learning-hero h1,
    .profile-journey h3 {
        overflow-wrap: anywhere;
    }

    .profile-learning-hero .profile-eyebrow {
        color: rgb(255 255 255 / 74%);
        letter-spacing: 0.08em;
    }

    .profile-learning-hero .profile-hero-copy {
        max-width: 42rem;
        color: rgb(255 255 255 / 84%);
        line-height: 1.7;
    }

    .profile-learning-hero .profile-role {
        display: inline-flex;
        border: 1px solid rgb(255 255 255 / 28%);
        border-radius: 999px;
        padding: 0.45rem 0.8rem;
        background: rgb(255 255 255 / 7%);
        color: #fff;
        font-size: 0.85rem;
    }

    .profile-learning-hero .profile-hero-mark {
        display: grid;
        width: 4.5rem;
        height: 4.5rem;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 1rem;
        background: rgb(255 255 255 / 10%);
        color: #fff;
        font-size: 1.4rem;
        font-weight: 800;
    }

    .profile-learning-hero .profile-hero-mark {
        border: 1px solid rgb(255 255 255 / 22%);
        border-radius: 50%;
        background: rgb(255 255 255 / 8%);
    }

    .profile-section-heading {
        color: var(--zap-ink);
    }

    .profile-journey-section {
        margin-bottom: 2.5rem;
    }

    .profile-progress-panel {
        padding: clamp(1.35rem, 3vw, 2rem);
        border: 1px solid var(--zap-border);
        border-radius: calc(var(--zap-radius) * 1.3);
        background: var(--zap-surface);
        box-shadow: var(--zap-shadow);
    }

    .profile-progress-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
    }

    .profile-progress-value {
        color: var(--zap-deep);
        font-size: clamp(3.4rem, 8vw, 5rem);
        font-weight: 850;
        letter-spacing: -0.06em;
        line-height: 1;
    }

    .profile-progress-panel .progress {
        height: 0.9rem;
        background: var(--zap-surface-tint);
    }

    .profile-progress-panel .progress-bar {
        border-radius: 999px;
        background: linear-gradient(90deg, var(--zap-primary), var(--zap-deep));
    }

    .profile-summary-strip {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-top: 1.5rem;
        border-top: 1px solid var(--zap-border);
        padding-top: 1.1rem;
    }

    .profile-summary-item {
        display: flex;
        align-items: baseline;
        gap: 0.55rem;
        min-width: 0;
    }

    .profile-summary-item + .profile-summary-item {
        margin-left: 1rem;
        border-left: 1px solid var(--zap-border);
        padding-left: 1rem;
    }

    .profile-summary-item strong {
        color: var(--zap-deep);
        font-size: 1.25rem;
        line-height: 1;
    }

    .profile-summary-item span {
        color: var(--zap-muted);
        font-size: 0.85rem;
    }

    .profile-achievement {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
        gap: 0.9rem;
        min-width: 0;
        padding: 1rem 1.1rem;
        border: 1px solid var(--zap-border);
        border-radius: 999px;
        background: var(--zap-surface);
        transition: border-color 150ms ease, transform 150ms ease;
    }

    .profile-achievement:hover {
        border-color: rgb(237 189 89 / 72%);
        transform: translateY(-2px);
    }

    .profile-achievement-mark {
        display: grid;
        width: 3.1rem;
        height: 3.1rem;
        flex: 0 0 auto;
        place-items: center;
        border: 1px solid rgb(237 189 89 / 66%);
        border-radius: 50%;
        background: #fbf5e8;
        color: var(--zap-deep);
        font-size: 0.7rem;
        font-weight: 800;
    }

    .profile-achievement-copy {
        min-width: 0;
    }

    .profile-achievement-copy h3 {
        overflow-wrap: anywhere;
    }

    .profile-achievement-count {
        color: var(--zap-deep);
        font-size: 1.6rem;
        font-weight: 850;
        line-height: 1;
    }

    .profile-journey {
        position: relative;
        overflow: hidden;
        border: 0;
        border-radius: calc(var(--zap-radius) * 1.5);
        background: var(--zap-deep);
        box-shadow: 0 0.85rem 1.8rem rgb(18 58 55 / 14%);
        color: #fff;
    }

    .profile-journey .profile-journey-label {
        color: #b7d7c6;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
    }

    .profile-journey h3,
    .profile-journey p {
        color: #fff;
    }

    .profile-journey .profile-journey-copy {
        color: rgb(255 255 255 / 78%);
    }

    .profile-journey-content {
        min-width: 0;
    }

    .profile-journey-progress {
        width: min(100%, 32rem);
    }

    .profile-journey-progress .progress {
        height: 0.65rem;
        background: rgb(255 255 255 / 20%);
    }

    .profile-journey-progress .progress-bar {
        border-radius: 999px;
        background: #b7d7c6;
    }

    .profile-journey .btn-primary {
        min-width: 11rem;
    }

    .profile-journey .btn-achievement {
        min-width: 11rem;
    }

    .profile-account-center {
        padding: clamp(1.25rem, 3vw, 1.75rem);
        border: 1px solid var(--zap-border);
        border-radius: calc(var(--zap-radius) * 1.3);
        background: var(--zap-surface);
        box-shadow: var(--zap-shadow);
    }

    .profile-account-name {
        color: var(--zap-deep);
        font-weight: 750;
    }

    .profile-account-role {
        display: inline-flex;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        background: var(--zap-surface-tint);
        color: var(--zap-deep);
        font-size: 0.82rem;
        font-weight: 700;
    }

    .profile-account-actions {
        height: 100%;
        padding: clamp(1.25rem, 3vw, 1.6rem);
        border: 1px solid var(--zap-border);
        border-top: 4px solid var(--zap-primary);
        border-radius: calc(var(--zap-radius) * 1.3);
        background: linear-gradient(145deg, var(--zap-surface-tint), var(--zap-surface));
    }

    .profile-account-logout {
        display: flex;
        width: 100%;
        min-height: 3.25rem;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1rem;
        text-align: left;
    }

    .profile-account-center dt {
        color: var(--zap-muted);
        font-weight: 600;
    }

    @media (max-width: 575.98px) {
        .profile-learning-hero .profile-hero-mark {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.75rem;
            font-size: 1.15rem;
        }

        .profile-achievement {
            border-radius: var(--zap-radius);
        }

        .profile-summary-item {
            flex-direction: column;
            gap: 0.3rem;
        }
    }
</style>

<header class="profile-learning-hero mb-4 mb-lg-5" aria-labelledby="profile-title">
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-4">
        <div>
            <p class="profile-eyebrow small fw-bold text-uppercase mb-2">Your learning profile</p>
            <h1 id="profile-title" class="display-6 fw-bold mb-2"><?= $userName !== '' ? $userName : 'Learner'; ?></h1>
            <p class="profile-hero-copy mb-0">Your learning identity, progress, and milestones in one place. Keep building practical skills to recognize phishing.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3 align-self-sm-center">
            <span class="profile-role"><?= $userRole !== '' ? $userRole : 'Learner'; ?></span>
            <span class="profile-hero-mark" aria-hidden="true">ZA</span>
        </div>
    </div>
</header>

<section class="profile-journey-section" aria-labelledby="profile-journey-title">
    <div class="mb-3">
        <p class="small fw-bold text-primary text-uppercase mb-2">Your next step</p>
        <h2 id="profile-journey-title" class="h4 fw-bold profile-section-heading mb-1">Learning journey</h2>
        <p class="small text-body-secondary mb-0">A clear next step to keep your progress moving.</p>
    </div>
    <article class="card profile-journey shadow-sm">
        <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4 p-4 p-lg-5">
            <div class="profile-journey-content flex-grow-1">
                <p class="profile-journey-label mb-2">
                    <?= $learningState === 'completed' ? 'Journey complete' : ($learningState === 'active' ? 'Continue learning' : 'Ready when you are'); ?>
                </p>
                <?php if ($learningState === 'completed'): ?>
                    <h3 class="h3 fw-bold mb-2">All available lessons completed</h3>
                    <p class="profile-journey-copy mb-0">Celebrate your achievement and explore your certificates.</p>
                <?php elseif ($nextLessonId > 0): ?>
                    <h3 class="h3 fw-bold mb-2">
                        <?= htmlspecialchars((string) ($learningHub['course_title'] ?? 'Your course'), ENT_QUOTES, 'UTF-8'); ?>
                    </h3>
                    <p class="profile-journey-copy mb-0">
                        Up next:
                        <strong><?= htmlspecialchars((string) ($learningHub['lesson_title'] ?? 'Continue your course'), ENT_QUOTES, 'UTF-8'); ?></strong>
                    </p>
                <?php else: ?>
                    <h3 class="h3 fw-bold mb-2">Choose your first learning path</h3>
                    <p class="profile-journey-copy mb-0">Explore available courses and start building your skills.</p>
                <?php endif; ?>
                <?php if ($learningState !== 'completed'): ?>
                    <div class="profile-journey-progress mt-4">
                        <div class="d-flex justify-content-between gap-3 mb-2">
                            <span class="small text-white">Overall lesson progress</span>
                            <span class="small text-white fw-semibold"><?= $progressPercent; ?>%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Overall learning progress"
                            aria-valuenow="<?= $progressPercent; ?>" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: <?= $progressPercent; ?>%"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($learningState === 'completed'): ?>
                <a class="btn btn-achievement btn-lg flex-shrink-0" href="?page=certificates">View certificates</a>
            <?php elseif ($nextLessonId > 0): ?>
                <a class="btn btn-primary btn-lg flex-shrink-0"
                    href="?page=lesson&amp;id=<?= $nextLessonId; ?>">Continue learning</a>
            <?php else: ?>
                <a class="btn btn-primary btn-lg flex-shrink-0" href="?page=courses">Explore courses</a>
            <?php endif; ?>
        </div>
    </article>
</section>

<section class="mb-4 mb-lg-5" aria-labelledby="profile-progress-title">
    <div class="mb-3">
        <p class="small fw-bold text-primary text-uppercase mb-2">Learning summary</p>
        <h2 id="profile-progress-title" class="h4 fw-bold profile-section-heading mb-1">Progress at a glance</h2>
    </div>
    <article class="profile-progress-panel">
        <div class="profile-progress-header mb-3">
            <div>
                <p class="small text-body-secondary fw-semibold mb-2">Overall lesson progress</p>
                <p class="profile-progress-value mb-0"><?= $progressPercent; ?><span class="fs-3">%</span></p>
            </div>
            <p class="small text-body-secondary mb-2">Across your available learning</p>
        </div>
        <div class="progress" role="progressbar" aria-label="Overall learning progress"
            aria-valuenow="<?= $progressPercent; ?>" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: <?= $progressPercent; ?>%"></div>
        </div>
        <div class="profile-summary-strip">
            <div class="profile-summary-item">
                <strong><?= $completedLessons; ?></strong>
                <span>Lessons completed</span>
            </div>
            <div class="profile-summary-item">
                <strong><?= (int) ($metrics['courses_available'] ?? 0); ?></strong>
                <span>Courses available</span>
            </div>
        </div>
    </article>
</section>

<section class="mb-5" aria-labelledby="profile-achievements-title">
    <div class="mb-3">
        <p class="small fw-bold text-primary text-uppercase mb-2">Practice milestones</p>
        <h2 id="profile-achievements-title" class="h4 fw-bold profile-section-heading mb-1">Achievements</h2>
        <p class="small text-body-secondary mb-0">Recognizing your progress through successful practice.</p>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
            <article class="profile-achievement">
                <span class="profile-achievement-mark" aria-hidden="true">QUIZ</span>
                <div class="profile-achievement-copy">
                    <h3 class="h6 fw-bold mb-1">Quizzes passed</h3>
                    <p class="small text-body-secondary mb-0">Knowledge checks completed successfully</p>
                </div>
                <span class="profile-achievement-count"><?= (int) ($metrics['quizzes_passed'] ?? 0); ?></span>
            </article>
        </div>
        <div class="col">
            <article class="profile-achievement">
                <span class="profile-achievement-mark" aria-hidden="true">SIM</span>
                <div class="profile-achievement-copy">
                    <h3 class="h6 fw-bold mb-1">Simulations passed</h3>
                    <p class="small text-body-secondary mb-0">Training scenarios completed successfully</p>
                </div>
                <span class="profile-achievement-count"><?= (int) ($metrics['simulations_passed'] ?? 0); ?></span>
            </article>
        </div>
    </div>
</section>

<section class="mt-5" aria-labelledby="profile-account-center-title">
    <div class="mb-3">
        <p class="small fw-bold text-body-secondary text-uppercase mb-2">Account center</p>
        <h2 id="profile-account-center-title" class="h4 fw-bold profile-section-heading mb-1">Your account</h2>
        <p class="small text-body-secondary mb-0">Manage your profile and account actions.</p>
    </div>
    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <article class="profile-account-center h-100">
                <p class="small fw-bold text-primary text-uppercase mb-3">Profile information</p>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9 profile-account-name"><?= $userName !== '' ? $userName : 'Learner'; ?></dd>
                    <dt class="col-sm-3 mb-sm-0">Role</dt>
                    <dd class="col-sm-9 mb-0"><span class="profile-account-role"><?= $userRole !== '' ? $userRole : 'Learner'; ?></span></dd>
                </dl>
            </article>
        </div>
        <div class="col-12 col-lg-5">
            <article class="profile-account-actions">
                <p class="small fw-bold text-primary text-uppercase mb-2">Account actions</p>
                <h3 class="h6 fw-bold mb-1">Session</h3>
                <p class="small text-body-secondary mb-0">You are signed in to your learning account.</p>
                <a class="btn btn-primary btn-lg profile-account-logout" href="?page=logout"
                    aria-label="Log out of Zona AntiPhishing">
                    <span>Log out</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </article>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';