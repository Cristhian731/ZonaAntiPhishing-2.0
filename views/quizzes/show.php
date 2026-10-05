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
require_once __DIR__ . '/../../app/controllers/CertificateController.php';
require_once __DIR__ . '/../../app/services/CsrfService.php';

$lessonController = new LessonController();
$lesson = $lessonController->getLesson((int) $lessonId);

if (!$lesson) {
    header('Location: ?page=courses');
    exit;
}

$courseId = (int) ($lesson['course_id'] ?? 0);

$quizController = new QuizController();
$certificateController = new CertificateController();
$quiz = $quizController->getQuizForLesson((int) $lessonId);
$quizResultKey = 'quiz_result_' . (int) $lessonId;
$result = $_SESSION[$quizResultKey] ?? null;
unset($_SESSION[$quizResultKey]);
$errorMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!CsrfService::validateToken($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your session expired or the request could not be verified. Please try again.';
    } else {
        $answers = $_POST['answers'] ?? [];

        if ($quiz && is_array($answers)) {
            $existingCertificateIds = [];

            foreach ($certificateController->getUserCertificates((int) $_SESSION['user_id']) as $certificateRecord) {
                if ((int) ($certificateRecord['course_id'] ?? 0) === $courseId) {
                    $existingCertificateIds[(int) $certificateRecord['id']] = true;
                }
            }

            $result = $quizController->submitQuiz(
                (int) $_SESSION['user_id'],
                (int) $lessonId,
                $answers
            );

            if ($result !== null) {
                if ($result['passed']) {
                    foreach ($certificateController->getUserCertificates((int) $_SESSION['user_id']) as $certificateRecord) {
                        $certificateRecordId = (int) ($certificateRecord['id'] ?? 0);

                        if (
                            (int) ($certificateRecord['course_id'] ?? 0) === $courseId
                            && !isset($existingCertificateIds[$certificateRecordId])
                        ) {
                            $result['new_certificate_id'] = $certificateRecordId;
                            break;
                        }
                    }
                }

                $_SESSION[$quizResultKey] = $result;
                header('Location: ?page=quiz&lesson_id=' . (int) $lessonId);
                exit;
            }
        }

        $errorMessage = 'This quiz has no questions available to score.';
    }
}

$celebrationCertificate = null;

if (
    is_array($result)
    && !empty($result['passed'])
    && isset($result['new_certificate_id'])
) {
    $candidateCertificate = $certificateController->getCertificate((int) $result['new_certificate_id']);

    if (
        $candidateCertificate !== null
        && (int) $candidateCertificate['user_id'] === (int) $_SESSION['user_id']
    ) {
        $celebrationCertificate = $candidateCertificate;
    }
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
    <?php if ($celebrationCertificate !== null): ?>
        <div id="certificate-celebration" class="certificate-celebration-backdrop">
            <div class="certificate-confetti" aria-hidden="true"></div>
            <section class="certificate-celebration-modal" role="dialog" aria-modal="true"
                aria-labelledby="certificate-celebration-title" aria-describedby="certificate-celebration-message">
                <button class="certificate-celebration-close" type="button" aria-label="Close certificate celebration">
                    <span aria-hidden="true">&#215;</span>
                </button>
                <div class="certificate-celebration-mark" aria-hidden="true">ZA</div>
                <p class="certificate-celebration-kicker mb-2">Achievement unlocked</p>
                <h2 id="certificate-celebration-title" class="h4 fw-bold mb-3" tabindex="-1">🏆 Certificate Unlocked</h2>
                <h3 class="certificate-celebration-title mb-3">Congratulations!</h3>
                <p id="certificate-celebration-message" class="certificate-celebration-copy mb-2">
                    You have successfully completed:
                </p>
                <p class="certificate-celebration-course mb-3">
                    <?= htmlspecialchars((string) $celebrationCertificate['course_title'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <p class="text-body-secondary mb-4">
                    Your dedication and effort have earned a certificate of completion.
                </p>
                <p class="certificate-celebration-score small text-body-secondary mb-4">
                    Quiz score: <strong><?= (int) $result['score']; ?>%</strong>
                    <span class="mx-2" aria-hidden="true">&#183;</span>
                    <?= (int) $result['correct_answers']; ?> of <?= (int) $result['total_questions']; ?> correct
                </p>
                <div class="certificate-celebration-actions">
                    <a class="btn btn-primary" href="?page=certificate&amp;id=<?= (int) $celebrationCertificate['id']; ?>">
                        View Certificate
                    </a>
                    <a class="btn btn-outline-primary"
                        href="?page=certificate-pdf&amp;id=<?= (int) $celebrationCertificate['id']; ?>">
                        Download PDF
                    </a>
                    <a class="btn btn-outline-secondary" href="?page=courses">Continue Learning</a>
                </div>
            </section>
        </div>
        <style>
            .certificate-celebration-backdrop {
                position: fixed;
                z-index: 1080;
                inset: 0;
                isolation: isolate;
                display: grid;
                place-items: center;
                padding: 1rem;
                overflow-y: auto;
                background: rgb(7 19 31 / 76%);
                backdrop-filter: blur(8px);
                animation: certificate-backdrop-in 350ms ease-out both;
            }

            .certificate-celebration-modal {
                position: relative;
                z-index: 2;
                width: min(920px, calc(100vw - 2rem));
                padding: 4rem 5rem 3.5rem;
                border: 1px solid var(--zap-border);
                border-top: 4px solid var(--zap-achievement);
                background: linear-gradient(145deg, var(--zap-surface) 0%, var(--zap-canvas) 100%);
                box-shadow: 0 2rem 6rem rgb(0 0 0 / 32%);
                color: var(--zap-ink);
                text-align: center;
                animation: certificate-modal-in 420ms cubic-bezier(0.2, 0.75, 0.25, 1) both;
            }

            .certificate-celebration-close {
                position: absolute;
                top: 0.85rem;
                right: 0.9rem;
                display: grid;
                width: 2.5rem;
                height: 2.5rem;
                place-items: center;
                border: 1px solid var(--zap-border);
                background: var(--zap-surface);
                color: var(--zap-muted);
                font-size: 1.55rem;
                line-height: 1;
            }

            .certificate-celebration-close:hover,
            .certificate-celebration-close:focus-visible {
                border-color: var(--zap-primary);
                color: var(--zap-deep);
            }

            .certificate-celebration-mark {
                display: grid;
                width: 4rem;
                height: 4rem;
                place-items: center;
                margin: 0 auto 1.25rem;
                border: 1px solid var(--zap-achievement);
                border-radius: 50%;
                color: var(--zap-deep);
                font-size: 0.95rem;
                font-weight: 800;
            }

            .certificate-celebration-kicker {
                color: #8a6b27;
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .certificate-celebration-modal h2 {
                color: var(--zap-deep);
            }

            .certificate-celebration-title {
                color: var(--zap-ink);
                font-family: Georgia, 'Times New Roman', serif;
                font-size: 2.8rem;
                font-weight: 600;
            }

            .certificate-celebration-copy,
            .certificate-celebration-course {
                font-size: 1.1rem;
            }

            .certificate-celebration-course {
                color: var(--zap-deep);
                font-weight: 750;
                overflow-wrap: anywhere;
            }

            .certificate-celebration-score {
                border-top: 1px solid var(--zap-border);
                padding-top: 1rem;
            }

            .certificate-celebration-actions {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.65rem;
            }

            .certificate-celebration-actions .btn {
                padding: 0.8rem 1.3rem;
            }

            .certificate-confetti {
                position: fixed;
                z-index: 1;
                inset: 0;
                overflow: hidden;
                pointer-events: none;
            }

            .certificate-confetti-piece {
                position: absolute;
                top: 0.5rem;
                width: 0.55rem;
                height: 1rem;
                border-radius: 1px;
                opacity: 0.9;
                animation: certificate-confetti-fall 3.8s cubic-bezier(0.22, 0.62, 0.35, 1) var(--delay) both;
            }

            @keyframes certificate-backdrop-in {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes certificate-modal-in {
                from {
                    opacity: 0;
                    transform: scale(0.94) translateY(0.75rem);
                }

                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            @keyframes certificate-confetti-fall {
                0% {
                    opacity: 0.9;
                }

                72% {
                    opacity: 0.75;
                }

                to {
                    opacity: 0;
                    transform: translate3d(var(--drift), var(--fall-distance), 0) rotate(var(--turn));
                }
            }

            @media (prefers-reduced-motion: reduce) {

                .certificate-celebration-backdrop,
                .certificate-celebration-modal {
                    animation: none;
                }

                .certificate-confetti-piece {
                    display: none;
                    animation: none;
                }
            }

            @media (max-width: 575.98px) {
                .certificate-celebration-modal {
                    padding: 3.25rem 1.25rem 1.75rem;
                }

                .certificate-celebration-mark {
                    width: 3.25rem;
                    height: 3.25rem;
                }

                .certificate-celebration-title {
                    font-size: 2rem;
                }

                .certificate-celebration-actions {
                    flex-direction: column;
                }

                .certificate-celebration-actions .btn {
                    width: 100%;
                }
            }
        </style>
        <script>
            (() => {
                const backdrop = document.getElementById('certificate-celebration');
                const confetti = backdrop?.querySelector('.certificate-confetti');
                const closeButton = backdrop?.querySelector('.certificate-celebration-close');
                const dialog = backdrop?.querySelector('.certificate-celebration-modal');
                const dialogTitle = backdrop?.querySelector('#certificate-celebration-title');
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                if (!backdrop || !confetti || !closeButton || !dialog) {
                    return;
                }

                const dismiss = () => {
                    backdrop.remove();
                    document.body.style.overflow = '';
                    document.removeEventListener('keydown', onKeyDown);
                };
                const onKeyDown = (event) => {
                    if (event.key === 'Escape') {
                        dismiss();
                        return;
                    }

                    if (event.key === 'Tab') {
                        const focusable = [...dialog.querySelectorAll('a[href], button:not([disabled])')];
                        const first = focusable[0];
                        const last = focusable[focusable.length - 1];

                        if (event.shiftKey && document.activeElement === first) {
                            event.preventDefault();
                            last?.focus();
                        } else if (!event.shiftKey && document.activeElement === last) {
                            event.preventDefault();
                            first?.focus();
                        }
                    }
                };

                document.body.style.overflow = 'hidden';
                document.addEventListener('keydown', onKeyDown);
                closeButton.addEventListener('click', dismiss);
                backdrop.addEventListener('click', (event) => {
                    if (event.target === backdrop) {
                        dismiss();
                    }
                });
                dialogTitle?.focus({ preventScroll: true });

                if (reducedMotion) {
                    return;
                }

                const tokens = getComputedStyle(document.documentElement);
                const colors = [
                    tokens.getPropertyValue('--zap-deep').trim(),
                    tokens.getPropertyValue('--zap-primary').trim(),
                    tokens.getPropertyValue('--zap-achievement').trim(),
                    '#ffffff',
                ];

                for (let index = 0; index < 64; index += 1) {
                    const piece = document.createElement('span');
                    piece.className = 'certificate-confetti-piece';
                    piece.style.left = `${Math.random() * 100}%`;
                    piece.style.backgroundColor = colors[index % colors.length];
                    piece.style.setProperty('--delay', `${Math.random() * 200}ms`);
                    piece.style.setProperty('--drift', `${Math.round(Math.random() * 180 - 90)}px`);
                    piece.style.setProperty('--fall-distance', `${70 + Math.random() * 40}vh`);
                    piece.style.setProperty('--turn', `${Math.round(Math.random() * 720 - 360)}deg`);
                    confetti.append(piece);
                }

                window.setTimeout(() => confetti.replaceChildren(), 5000);
            })();
        </script>
    <?php else: ?>
        <section class="alert <?= $result['passed'] ? 'alert-success' : 'alert-danger'; ?>" role="status">
            <h2 class="h4 fw-bold"><?= $result['passed'] ? 'Quiz passed' : 'Quiz not passed'; ?></h2>
            <p class="mb-1">Your score: <strong><?= (int) $result['score']; ?>%</strong></p>
            <p class="mb-0">
                Correct answers: <?= (int) $result['correct_answers']; ?> of <?= (int) $result['total_questions']; ?>
            </p>
        </section>
    <?php endif; ?>
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
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(CsrfService::generateToken(), ENT_QUOTES, 'UTF-8'); ?>">
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