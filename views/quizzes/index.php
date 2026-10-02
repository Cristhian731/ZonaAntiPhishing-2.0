<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

require_once __DIR__ . '/../../app/controllers/QuizController.php';

$quizController = new QuizController();
$quizzes = $quizController->getQuizzesForUser((int) $_SESSION['user_id']);
$title = 'My Quizzes | Zona AntiPhishing';
$activePage = 'quizzes';

$statusDisplay = [
    'passed' => ['label' => 'Passed', 'class' => 'success', 'icon' => '&#9989;'],
    'failed' => ['label' => 'Failed', 'class' => 'danger', 'icon' => '&#10060;'],
    'not_attempted' => ['label' => 'Not Attempted', 'class' => 'warning', 'icon' => '&#9203;'],
];

ob_start();
?>

<header class="mb-4 mb-lg-5">
    <p class="small fw-bold text-primary text-uppercase mb-2">Learning progress</p>
    <h1 class="display-6 fw-bold mb-2">My Quizzes</h1>
    <p class="text-body-secondary mb-0">Review your quiz status and continue learning.</p>
</header>

<?php if ($quizzes === []): ?>
    <div class="alert alert-light border mb-0" role="status">
        No quizzes are available yet.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th scope="col">Quiz Title</th>
                    <th scope="col">Course</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="visually-hidden">Action</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizzes as $quiz): ?>
                    <?php
                    $status = $statusDisplay[$quiz['status'] ?? 'not_attempted'] ?? $statusDisplay['not_attempted'];
                    $quizTitle = (string) ($quiz['quiz_title'] ?? 'Untitled quiz');
                    $courseTitle = (string) ($quiz['course_title'] ?? 'Course unavailable');
                    $lessonId = (int) ($quiz['lesson_id'] ?? 0);
                    ?>
                    <tr>
                        <th scope="row">
                            <?= htmlspecialchars($quizTitle, ENT_QUOTES, 'UTF-8'); ?>
                        </th>
                        <td><?= htmlspecialchars($courseTitle, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <span class="badge text-bg-<?= $status['class']; ?>">
                                <span aria-hidden="true"><?= $status['icon']; ?></span>
                                <?= htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <?php if ($lessonId > 0): ?>
                                <a class="btn btn-primary btn-sm" href="?page=quiz&amp;lesson_id=<?= $lessonId; ?>">Open Quiz</a>
                            <?php else: ?>
                                <span class="small text-body-secondary">Unavailable</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';