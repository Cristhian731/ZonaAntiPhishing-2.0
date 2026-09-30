<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

require_once __DIR__ . '/../../app/controllers/CourseController.php';

$courseController = new CourseController();
$courses = $courseController->getCourses();
$courses = is_array($courses) ? $courses : [];

$title = 'Courses | Zona AntiPhishing';
$activePage = 'courses';

ob_start();
?>

<header class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4 mb-lg-5">
    <div>
        <p class="small fw-bold text-primary text-uppercase mb-2">Learning library</p>
        <h1 class="display-6 fw-bold mb-2">Courses</h1>
        <p class="text-body-secondary mb-0">Explore the available phishing awareness courses.</p>
    </div>

    <span class="badge rounded-pill text-bg-light border text-dark px-3 py-2">
        <?= count($courses); ?> <?= count($courses) === 1 ? 'course' : 'courses'; ?>
    </span>
</header>

<?php if ($courses === []): ?>
    <div class="alert alert-light border mb-0" role="status">
        No courses are available yet.
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 g-lg-4">
        <?php foreach ($courses as $course): ?>
            <?php
            $courseTitle = htmlspecialchars((string) ($course['title'] ?? 'Untitled course'), ENT_QUOTES, 'UTF-8');
            $courseDescription = htmlspecialchars((string) ($course['description'] ?? ''), ENT_QUOTES, 'UTF-8');
            $courseLevel = htmlspecialchars(ucfirst((string) ($course['level'] ?? 'beginner')), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="col">
                <article class="card dashboard-stat h-100 shadow-sm">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <h2 class="h5 fw-bold mb-0"><?= $courseTitle; ?></h2>
                            <span class="badge text-bg-primary flex-shrink-0"><?= $courseLevel; ?></span>
                        </div>
                        <p class="card-text text-body-secondary mb-0">
                            <?= $courseDescription !== '' ? $courseDescription : 'No description available.'; ?>
                        </p>
                        <div class="mt-auto pt-4">
                            <a class="btn btn-primary" href="?page=course&amp;id=<?= (int) ($course['id'] ?? 0); ?>">
                                View Course
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
