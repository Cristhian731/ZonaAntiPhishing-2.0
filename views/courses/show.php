<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$courseId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($courseId === false || $courseId === null || $courseId < 1) {
    header('Location: ?page=courses');
    exit;
}

require_once __DIR__ . '/../../app/controllers/CourseController.php';
require_once __DIR__ . '/../../app/controllers/LessonController.php';

$courseController = new CourseController();
$course = $courseController->getCourse((int) $courseId);

if (!$course) {
    header('Location: ?page=courses');
    exit;
}

$lessonController = new LessonController();
$lessons = $lessonController->getLessonsByCourse((int) $courseId);
$lessons = is_array($lessons) ? $lessons : [];

$title = (string) ($course['title'] ?? 'Course') . ' | Zona AntiPhishing';
$activePage = 'courses';
$courseTitle = htmlspecialchars((string) ($course['title'] ?? 'Untitled course'), ENT_QUOTES, 'UTF-8');
$courseDescription = htmlspecialchars((string) ($course['description'] ?? ''), ENT_QUOTES, 'UTF-8');
$courseLevelValue = strtolower(trim((string) ($course['level'] ?? 'beginner')));
$courseLevel = htmlspecialchars(ucfirst($courseLevelValue), ENT_QUOTES, 'UTF-8');
$courseLevelClass = in_array($courseLevelValue, ['beginner', 'intermediate', 'advanced'], true)
    ? 'course-level-' . $courseLevelValue
    : '';
$lessonCount = count($lessons);

ob_start();
?>

<header class="course-hero course-detail-hero p-4 p-lg-5 mb-4 mb-lg-5">
    <a class="link-primary text-decoration-none fw-semibold" href="?page=courses">&larr; Back to courses</a>

    <div class="mt-4">
        <p class="small fw-bold text-primary text-uppercase mb-3">Professional learning path</p>
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-4">
            <div class="flex-grow-1 course-detail-copy">
                <h1 class="display-5 fw-bold mb-3"><?= $courseTitle; ?></h1>
                <p class="course-detail-description text-body-secondary mb-0">
                    <?= $courseDescription !== '' ? nl2br($courseDescription) : 'No description available.'; ?>
                </p>
            </div>
            <span class="course-level-badge course-detail-level <?= $courseLevelClass; ?>">
                <?= $courseLevel; ?>
            </span>
        </div>

        <div class="course-detail-footer d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4 mt-lg-5">
            <span class="course-detail-count">
                <strong><?= $lessonCount; ?></strong>
                <?= $lessonCount === 1 ? 'lesson' : 'lessons'; ?> in this course
            </span>
            <a class="btn btn-primary btn-lg course-detail-cta" href="#lessons-title">
                Explore lessons <span aria-hidden="true">&darr;</span>
            </a>
        </div>
    </div>
</header>

<section aria-labelledby="lessons-title">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h2 id="lessons-title" class="h4 fw-bold mb-1">Lessons</h2>
            <p class="small text-body-secondary mb-0">Work through the lessons in order.</p>
        </div>
        <span class="badge rounded-pill text-bg-light border text-dark px-3 py-2">
            <?= $lessonCount; ?> <?= $lessonCount === 1 ? 'lesson' : 'lessons'; ?>
        </span>
    </div>

    <?php if ($lessons === []): ?>
        <div class="alert alert-light border mb-0" role="status">
            No lessons are available for this course yet.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-lg-2 g-3">
            <?php foreach ($lessons as $index => $lesson): ?>
                <?php
                $lessonTitle = htmlspecialchars((string) ($lesson['title'] ?? 'Untitled lesson'), ENT_QUOTES, 'UTF-8');
                $estimatedMinutes = max(0, (int) ($lesson['estimated_minutes'] ?? 0));
                ?>
                <div class="col">
                    <article class="card dashboard-stat course-lesson-card h-100 shadow-sm">
                        <div class="card-body d-flex align-items-start gap-3 p-4">
                            <span class="course-lesson-number" aria-hidden="true">
                                <?= (int) $index + 1; ?>
                            </span>
                            <div class="flex-grow-1 course-lesson-copy">
                                <p class="small fw-bold text-primary text-uppercase mb-2">
                                    Lesson <?= (int) $index + 1; ?>
                                </p>
                                <h3 class="h5 fw-bold mb-2"><?= $lessonTitle; ?></h3>
                                <p class="small text-body-secondary mb-0">
                                    <?= $estimatedMinutes > 0 ? $estimatedMinutes . ' min read' : 'Reading time not set'; ?>
                                </p>
                                <div class="mt-3">
                                    <a class="btn btn-outline-primary btn-sm"
                                        href="?page=lesson&amp;id=<?= (int) ($lesson['id'] ?? 0); ?>">
                                        Start lesson <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
