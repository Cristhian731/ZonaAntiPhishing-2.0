<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$lessonId = filter_var($_GET['lesson_id'] ?? null, FILTER_VALIDATE_INT);

if ($lessonId === false || $lessonId === null || $lessonId < 1) {
    header('Location: ?page=courses');
    exit;
}

require_once __DIR__ . '/../../app/controllers/LessonController.php';
require_once __DIR__ . '/../../app/controllers/QuizController.php';

$lessonController = new LessonController();
$lesson = $lessonController->getLesson((int) $lessonId);

if (!$lesson) {
    header('Location: ?page=courses');
    exit;
}

$courseId = (int) ($lesson['course_id'] ?? 0);

$quizController = new QuizController();
$quiz = $quizController->getQuizForLesson((int) $lessonId);
$quizResultKey = 'quiz_result_' . (int) $lessonId;
$result = $_SESSION[$quizResultKey] ?? null;
unset($_SESSION[$quizResultKey]);
$errorMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $answers = $_POST['answers'] ?? [];

    if ($quiz && is_array($answers)) {
        $result = $quizController->submitQuiz(
            (int) $_SESSION['user_id'],
            (int) $lessonId,
            $answers
        );

        if ($result !== null) {
            $_SESSION[$quizResultKey] = $result;
            header('Location: ?page=quiz&lesson_id=' . (int) $lessonId);
            exit;
        }
    }

    $errorMessage = 'This quiz has no questions available to score.';
}

$questions = is_array($quiz['questions'] ?? null) ? $quiz['questions'] : [];
$title = htmlspecialchars((string) ($quiz['title'] ?? 'Quiz'), ENT_QUOTES, 'UTF-8') . ' | Zona AntiPhishing';
$activePage = 'courses';
$quizTitle = htmlspecialchars((string) ($quiz['title'] ?? 'Quiz'), ENT_QUOTES, 'UTF-8');
$quizDescription = htmlspecialchars((string) ($quiz['description'] ?? ''), ENT_QUOTES, 'UTF-8');
$passingScore = max(0, min(100, (int) ($quiz['passing_score'] ?? 70)));

ob_start();
?>

<header class="mb-4 mb-lg-5">
    <a class="link-primary text-decoration-none fw-semibold" href="?page=course&amp;id=<?= $courseId; ?>">
        &larr; Back to course details
    </a>

    <div class="mt-4">
        <p class="small fw-bold text-primary text-uppercase mb-2">Lesson quiz</p>
        <h1 class="display-6 fw-bold mb-2"><?= $quizTitle; ?></h1>
        <?php if ($quizDescription !== ''): ?>
            <p class="text-body-secondary mb-2"><?= nl2br($quizDescription); ?></p>
        <?php endif; ?>
        <?php if ($quiz): ?>
            <p class="small text-body-secondary mb-0">Passing score: <?= $passingScore; ?>%</p>
        <?php endif; ?>
    </div>
</header>

<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-warning" role="alert">
        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if ($result !== null): ?>
    <section class="alert <?= $result['passed'] ? 'alert-success' : 'alert-danger'; ?>" role="status">
        <h2 class="h4 fw-bold"><?= $result['passed'] ? 'Quiz passed' : 'Quiz not passed'; ?></h2>
        <p class="mb-1">Your score: <strong><?= (int) $result['score']; ?>%</strong></p>
        <p class="mb-0">
            Correct answers: <?= (int) $result['correct_answers']; ?> of <?= (int) $result['total_questions']; ?>
        </p>
    </section>
<?php elseif (!$quiz): ?>
    <div class="alert alert-light border" role="status">
        No quiz is available for this lesson yet.
    </div>
<?php elseif ($questions === []): ?>
    <div class="alert alert-light border" role="status">
        This quiz does not have any questions yet.
    </div>
<?php else: ?>
    <form method="POST" action="?page=quiz&amp;lesson_id=<?= (int) $lessonId; ?>">
        <?php foreach ($questions as $questionIndex => $question): ?>
            <?php
            $questionId = (int) ($question['id'] ?? 0);
            $questionText = htmlspecialchars((string) ($question['question_text'] ?? ''), ENT_QUOTES, 'UTF-8');
            $options = is_array($question['options'] ?? null) ? $question['options'] : [];
            ?>
            <fieldset class="card dashboard-stat mb-3 shadow-sm">
                <legend class="visually-hidden">
                    Question <?= (int) $questionIndex + 1; ?>
                </legend>
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">
                        <span class="text-primary me-2"><?= (int) $questionIndex + 1; ?>.</span>
                        <?= $questionText; ?>
                    </h2>

                    <?php if ($options === []): ?>
                        <p class="small text-body-secondary mb-0">No answer options are available.</p>
                    <?php else: ?>
                        <div class="d-grid gap-2">
                            <?php foreach ($options as $option): ?>
                                <?php
                                $optionId = (int) ($option['id'] ?? 0);
                                $optionText = htmlspecialchars((string) ($option['option_text'] ?? ''), ENT_QUOTES, 'UTF-8');
                                $inputId = 'question-' . $questionId . '-option-' . $optionId;
                                ?>
                                <div class="form-check border rounded-2 px-5 py-3">
                                    <input class="form-check-input" type="radio" name="answers[<?= $questionId; ?>]"
                                        id="<?= htmlspecialchars($inputId, ENT_QUOTES, 'UTF-8'); ?>" value="<?= $optionId; ?>" required>
                                    <label class="form-check-label w-100" for="<?= htmlspecialchars($inputId, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?= $optionText; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>
        <?php endforeach; ?>

        <button class="btn btn-primary btn-lg" type="submit">Submit Quiz</button>
    </form>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
