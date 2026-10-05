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
$sectionTitles = [
    'Introduction',
    'Main Concepts',
    'Real Example',
    'How to Protect Yourself',
    'Key Takeaways',
];
$lessonSections = [];
$currentSection = null;

foreach (preg_split('/\R/', $lessonContent) ?: [] as $contentLine) {
    $heading = trim($contentLine);

    if (in_array($heading, $sectionTitles, true)) {
        $currentSection = $heading;
        $lessonSections[$currentSection] = [];
        continue;
    }

    if ($currentSection !== null) {
        $lessonSections[$currentSection][] = $contentLine;
    }
}

$summary = trim((string) ($lesson['summary'] ?? ''));
$title = (string) ($lesson['title'] ?? 'Lesson') . ' | Zona AntiPhishing';
$activePage = 'courses';

ob_start();
?>

<header class="learning-sequence-header p-4 p-lg-5 mb-4 mb-lg-5">
    <a class="link-primary text-decoration-none fw-semibold" href="?page=course&amp;id=<?= $courseId; ?>">
        Back to <?= htmlspecialchars((string) ($course['title'] ?? 'course'), ENT_QUOTES, 'UTF-8'); ?>
    </a>

    <div class="mt-4">
        <p class="small fw-bold text-primary text-uppercase mb-3 learning-course-name">
            <?= htmlspecialchars((string) ($course['title'] ?? 'Course'), ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <h1 class="display-5 fw-bold mb-3 learning-lesson-title">
            <?= htmlspecialchars((string) ($lesson['title'] ?? 'Untitled lesson'), ENT_QUOTES, 'UTF-8'); ?>
        </h1>
        <div class="d-flex flex-wrap align-items-center gap-3 text-body-secondary">
            <span class="badge text-bg-light border text-dark px-3 py-2">
                Lesson <?= $lessonPosition + 1; ?> of <?= count($lessons); ?>
            </span>
            <span class="learning-reading-time" aria-label="Estimated reading time">
                <?= $estimatedMinutes > 0 ? $estimatedMinutes . ' min read' : 'Reading time not set'; ?>
            </span>
        </div>
        <div class="learning-sequence-progress mt-4" aria-label="Position in course">
            <div class="progress" role="progressbar" aria-label="Course sequence position"
                aria-valuenow="<?= $lessonPosition + 1; ?>" aria-valuemin="1" aria-valuemax="<?= count($lessons); ?>">
                <div class="progress-bar"
                    style="width: <?= (int) round((($lessonPosition + 1) / max(1, count($lessons))) * 100); ?>%"></div>
            </div>
        </div>
        <section class="lesson-objective mt-4 mt-lg-5 p-3 p-lg-4"
            aria-labelledby="lesson-summary-title">
            <p class="small fw-bold text-primary text-uppercase mb-2">Learning objective</p>
            <h2 id="lesson-summary-title" class="h5 fw-bold mb-2">Key ideas</h2>
            <?php if ($summary !== ''): ?>
                <p class="mb-0"><?= nl2br(htmlspecialchars($summary, ENT_QUOTES, 'UTF-8')); ?></p>
            <?php else: ?>
                <p class="mb-0 text-body-secondary">An objective has not been provided for this lesson yet.</p>
            <?php endif; ?>
        </section>
    </div>
</header>

<article class="card dashboard-stat lesson-content-card shadow-sm mb-4" aria-labelledby="lesson-content-title">
    <div class="card-body p-4 p-lg-5">
        <h2 id="lesson-content-title" class="h4 fw-bold mb-4">Lesson content</h2>
        <?php if ($lessonContent === ''): ?>
            <p class="text-body-secondary mb-0">Lesson content is not available yet.</p>
        <?php elseif ($lessonSections === []): ?>
            <div class="lesson-content">
                <?php foreach (preg_split('/(?:\r\n|\r|\n)\s*(?:\r\n|\r|\n)+/', $lessonContent) ?: [] as $paragraph): ?>
                    <?php if (trim($paragraph) !== ''): ?>
                        <p><?= nl2br(htmlspecialchars(trim($paragraph), ENT_QUOTES, 'UTF-8')); ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="lesson-content">
                <?php foreach ($sectionTitles as $sectionTitle): ?>
                    <?php if (!isset($lessonSections[$sectionTitle])): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <?php
                    $sectionLines = array_values(array_filter(
                        array_map('trim', $lessonSections[$sectionTitle]),
                        static fn(string $line): bool => $line !== ''
                    ));
                    $isRecommendationList = $sectionTitle === 'How to Protect Yourself';
                    $isTakeawayList = $sectionTitle === 'Key Takeaways';
                    ?>
                    <section class="mb-4"
                        aria-labelledby="lesson-section-<?= (int) array_search($sectionTitle, $sectionTitles, true); ?>">
                        <h3 id="lesson-section-<?= (int) array_search($sectionTitle, $sectionTitles, true); ?>"
                            class="h5 fw-bold mb-3">
                            <?= htmlspecialchars($sectionTitle, ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <?php if ($isRecommendationList || $isTakeawayList): ?>
                            <?php $listItems = array_map(
                                static fn(string $line): string => preg_replace('/^(?:\d+\.|[-*])\s+/', '', $line) ?? $line,
                                $sectionLines
                            ); ?>
                            <?php if ($isRecommendationList): ?>
                                <ol class="mb-0">
                                    <?php foreach ($listItems as $listItem): ?>
                                        <li class="mb-2"><?= htmlspecialchars($listItem, ENT_QUOTES, 'UTF-8'); ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php else: ?>
                                <ul class="mb-0">
                                    <?php foreach ($listItems as $listItem): ?>
                                        <li class="mb-2"><?= htmlspecialchars($listItem, ENT_QUOTES, 'UTF-8'); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php foreach (preg_split('/\R\s*\R/', implode("\n", $sectionLines)) ?: [] as $paragraph): ?>
                                <?php if (trim($paragraph) !== ''): ?>
                                    <p><?= nl2br(htmlspecialchars(trim($paragraph), ENT_QUOTES, 'UTF-8')); ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </section>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</article>

<section class="quiz-transition p-4 p-lg-5 mb-4" aria-labelledby="lesson-quiz-title">
    <?php if ($quiz): ?>
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
            <div>
                <p class="small fw-bold text-primary text-uppercase mb-2">Knowledge check</p>
                <h2 id="lesson-quiz-title" class="h3 fw-bold mb-2">Ready to test what you&apos;ve learned?</h2>
                <p class="text-body-secondary mb-0">Apply these ideas in a short quiz, then continue learning.</p>
            </div>
            <a class="btn btn-primary btn-lg quiz-transition-cta flex-shrink-0"
                href="?page=quiz&amp;lesson_id=<?= (int) $lessonId; ?>">
                Start Quiz <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    <?php else: ?>
        <h2 id="lesson-quiz-title" class="h5 fw-bold mb-2">Check your understanding</h2>
        <p class="text-body-secondary mb-0">No quiz available for this lesson yet.</p>
    <?php endif; ?>
</section>

<nav class="lesson-navigation d-flex flex-column flex-sm-row justify-content-between gap-2 mb-4"
    aria-label="Lesson navigation">
    <div>
        <?php if ($previousLesson): ?>
            <a class="btn btn-outline-primary" href="?page=lesson&amp;id=<?= (int) $previousLesson['id']; ?>">
                &larr; Previous lesson
            </a>
        <?php endif; ?>
    </div>
    <div>
        <?php if ($nextLesson): ?>
            <a class="btn btn-outline-primary" href="?page=lesson&amp;id=<?= (int) $nextLesson['id']; ?>">
                Continue to next lesson <span aria-hidden="true">&rarr;</span>
            </a>
        <?php endif; ?>
    </div>
</nav>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';