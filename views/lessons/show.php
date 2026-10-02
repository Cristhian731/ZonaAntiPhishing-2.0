<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$lessonId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($lessonId === false || $lessonId === null || $lessonId < 1) {
    header('Location: ?page=courses');
    exit;
}

require_once __DIR__ . '/../../app/controllers/LessonController.php';
require_once __DIR__ . '/../../app/controllers/CourseController.php';
require_once __DIR__ . '/../../app/controllers/QuizController.php';

$lessonController = new LessonController();
$lesson = $lessonController->getLesson($lessonId);

if (!$lesson) {
    header('Location: ?page=courses');
    exit;
}

$courseId = (int) ($lesson['course_id'] ?? 0);
$courseController = new CourseController();
$course = $courseController->getCourse($courseId);

if (!$course) {
    header('Location: ?page=courses');
    exit;
}

$lessons = $lessonController->getLessonsByCourse($courseId);
$lessons = is_array($lessons) ? $lessons : [];

usort($lessons, static function (array $first, array $second): int {
    $orderComparison = (int) ($first['lesson_order'] ?? 0) <=> (int) ($second['lesson_order'] ?? 0);

    return $orderComparison !== 0
        ? $orderComparison
        : ((int) ($first['id'] ?? 0) <=> (int) ($second['id'] ?? 0));
});

$lessonPosition = null;

foreach ($lessons as $index => $courseLesson) {
    if ((int) ($courseLesson['id'] ?? 0) === (int) $lessonId) {
        $lessonPosition = $index;
        break;
    }
}

if ($lessonPosition === null) {
    header('Location: ?page=courses');
    exit;
}

$previousLesson = $lessons[$lessonPosition - 1] ?? null;
$nextLesson = $lessons[$lessonPosition + 1] ?? null;
$quizController = new QuizController();
$quiz = $quizController->getQuizForLesson($lessonId);
$estimatedMinutes = max(0, (int) ($lesson['estimated_minutes'] ?? 0));
$lessonContent = trim((string) ($lesson['content'] ?? ''));
$contentParagraphs = preg_split('/(?:\r\n|\r|\n)\s*(?:\r\n|\r|\n)+/', $lessonContent) ?: [];
$summary = trim((string) ($lesson['summary'] ?? ''));
$title = (string) ($lesson['title'] ?? 'Lesson') . ' | Zona AntiPhishing';
$activePage = 'courses';

ob_start();
?>

<header class="mb-4 mb-lg-5">
    <a class="link-primary text-decoration-none fw-semibold" href="?page=course&amp;id=<?= $courseId; ?>">
        Back to <?= htmlspecialchars((string) ($course['title'] ?? 'course'), ENT_QUOTES, 'UTF-8'); ?>
    </a>

    <div class="mt-4">
        <p class="small fw-bold text-primary text-uppercase mb-2">
            Lesson <?= $lessonPosition + 1; ?> of <?= count($lessons); ?>
        </p>
        <h1 class="display-6 fw-bold mb-2">
            <?= htmlspecialchars((string) ($lesson['title'] ?? 'Untitled lesson'), ENT_QUOTES, 'UTF-8'); ?>
        </h1>
        <div class="d-flex flex-wrap align-items-center gap-3 text-body-secondary">
            <a class="link-secondary" href="?page=course&amp;id=<?= $courseId; ?>">
                <?= htmlspecialchars((string) ($course['title'] ?? 'Course'), ENT_QUOTES, 'UTF-8'); ?>
            </a>
            <span aria-label="Estimated duration">
                <?= $estimatedMinutes > 0 ? $estimatedMinutes . ' min' : 'Duration not set'; ?>
            </span>
        </div>
    </div>
</header>

<?php if ($summary !== ''): ?>
    <section class="alert alert-light border mb-4" aria-labelledby="lesson-summary-title">
        <h2 id="lesson-summary-title" class="h5 fw-bold">Summary</h2>
        <p class="mb-0">
            <?= nl2br(htmlspecialchars($summary, ENT_QUOTES, 'UTF-8')); ?>
        </p>
    </section>
<?php endif; ?>

<article class="card dashboard-stat shadow-sm mb-4" aria-labelledby="lesson-content-title">
    <div class="card-body p-4 p-lg-5">
        <h2 id="lesson-content-title" class="h4 fw-bold mb-4">Lesson content</h2>
        <?php if ($contentParagraphs === [] || (count($contentParagraphs) === 1 && trim($contentParagraphs[0]) === '')): ?>
            <p class="text-body-secondary mb-0">Lesson content is not available yet.</p>
        <?php else: ?>
            <div class="lesson-content">
                <?php foreach ($contentParagraphs as $paragraph): ?>
                    <?php if (trim($paragraph) !== ''): ?>
                        <p><?= nl2br(htmlspecialchars(trim($paragraph), ENT_QUOTES, 'UTF-8')); ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</article>

<nav class="d-flex flex-column flex-sm-row justify-content-between gap-2 mb-4" aria-label="Lesson navigation">
    <div>
        <?php if ($previousLesson): ?>
            <a class="btn btn-outline-primary" href="?page=lesson&amp;id=<?= (int) $previousLesson['id']; ?>">Previous
                lesson</a>
        <?php endif; ?>
    </div>
    <div>
        <?php if ($nextLesson): ?>
            <a class="btn btn-outline-primary" href="?page=lesson&amp;id=<?= (int) $nextLesson['id']; ?>">Next lesson</a>
        <?php endif; ?>
    </div>
</nav>

<section class="border-top pt-4" aria-labelledby="lesson-quiz-title">
    <h2 id="lesson-quiz-title" class="h5 fw-bold mb-3">Check your understanding</h2>
    <?php if ($quiz): ?>
        <a class="btn btn-primary" href="?page=quiz&amp;lesson_id=<?= (int) $lessonId; ?>">Start Quiz</a>
    <?php else: ?>
        <p class="text-body-secondary mb-0">No quiz available for this lesson yet</p>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';