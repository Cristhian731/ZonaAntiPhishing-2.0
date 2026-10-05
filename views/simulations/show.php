<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$simulationId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($simulationId === false || $simulationId === null || $simulationId < 1) {
    header('Location: ?page=simulations');
    exit;
}

require_once __DIR__ . '/../../app/controllers/SimulationController.php';
require_once __DIR__ . '/../../app/services/CsrfService.php';

$simulationController = new SimulationController();
$simulation = $simulationController->getSimulation((int) $simulationId);

if (!$simulation) {
    header('Location: ?page=simulations');
    exit;
}

$scenarios = $simulationController->getScenarios((int) $simulationId);
$simulationResultKey = 'simulation_result_' . (int) $simulationId;
$simulationProgressKey = 'simulation_progress_' . (int) $simulationId;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && ($_GET['restart'] ?? null) === '1') {
    unset($_SESSION[$simulationProgressKey], $_SESSION[$simulationResultKey]);
    header('Location: ?page=simulation&id=' . (int) $simulationId);
    exit;
}

$result = $_SESSION[$simulationResultKey] ?? null;
unset($_SESSION[$simulationResultKey]);
$progress = $_SESSION[$simulationProgressKey] ?? ['answers' => []];
$progressAnswers = is_array($progress['answers'] ?? null) ? $progress['answers'] : [];
$errorMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!CsrfService::validateToken($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your session expired or the request could not be verified. Please try again.';
    } elseif (count($scenarios) !== 5) {
        $errorMessage = 'This simulation is not configured with five scenarios yet.';
    } else {
        $expectedIndex = count($progressAnswers);
        $expectedScenario = $scenarios[$expectedIndex] ?? null;
        $postedStepOrder = filter_var($_POST['step_order'] ?? null, FILTER_VALIDATE_INT);
        $postedScenarioId = filter_var($_POST['scenario_id'] ?? null, FILTER_VALIDATE_INT);
        $answer = $_POST['answer'] ?? null;

        if (
            is_array($expectedScenario)
            && $postedStepOrder !== false
            && (int) $postedStepOrder === (int) ($expectedScenario['step_order'] ?? 0)
            && $postedScenarioId !== false
            && (int) $postedScenarioId === (int) ($expectedScenario['id'] ?? 0)
            && is_string($answer)
            && in_array(strtolower(trim($answer)), ['phishing', 'legitimate'], true)
        ) {
            $scenarioId = (int) $expectedScenario['id'];
            $progressAnswers[$scenarioId] = strtolower(trim($answer));

            if (count($progressAnswers) < 5) {
                $_SESSION[$simulationProgressKey] = ['answers' => $progressAnswers];
                header('Location: ?page=simulation&id=' . (int) $simulationId);
                exit;
            }

            $result = $simulationController->submitSimulation(
                (int) $_SESSION['user_id'],
                (int) $simulationId,
                $progressAnswers
            );

            if ($result !== null) {
                unset($_SESSION[$simulationProgressKey]);
                $_SESSION[$simulationResultKey] = $result;
                header('Location: ?page=simulation&id=' . (int) $simulationId);
                exit;
            }

            unset($progressAnswers[$scenarioId]);
            $_SESSION[$simulationProgressKey] = ['answers' => $progressAnswers];
            $errorMessage = 'Your decisions could not be scored. Please retry the final step.';
        } else {
            $errorMessage = 'Select a valid answer for the current scenario and try again.';
        }
    }
}

$scenarioCountValid = count($scenarios) === 5;
$currentStepIndex = count($progressAnswers);
$currentScenario = $scenarioCountValid ? ($scenarios[$currentStepIndex] ?? null) : null;
$title = (string) ($simulation['title'] ?? 'Simulation') . ' | Zona AntiPhishing';
$activePage = 'simulations';
$simulationTitle = htmlspecialchars((string) ($simulation['title'] ?? 'Simulation'), ENT_QUOTES, 'UTF-8');

ob_start();
?>

<header class="mb-4 mb-lg-5">
    <a class="link-primary text-decoration-none fw-semibold" href="?page=simulations">&larr; Back to simulations</a>

    <div class="mt-4">
        <p class="small fw-bold text-primary text-uppercase mb-2">Phishing recognition exercise</p>
        <h1 class="display-6 fw-bold mb-2"><?= $simulationTitle; ?></h1>
        <p class="text-body-secondary mb-0">
            <?= htmlspecialchars((string) ($simulation['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
        </p>
    </div>
</header>

<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-warning" role="alert">
        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!$scenarioCountValid): ?>
    <div class="alert alert-light border" role="status">
        This simulation is not configured with five scenarios yet.
    </div>
<?php elseif (is_array($result)): ?>
    <section class="border bg-white p-4 p-lg-5" aria-labelledby="simulation-result-title">
        <p class="small fw-bold text-primary text-uppercase mb-2">Final result</p>
        <h2 id="simulation-result-title" class="h2 fw-bold mb-3">Simulation Completed</h2>
        <p class="h4 mb-2">
            Correct Decisions:
            <strong><?= (int) $result['correct_count']; ?>/<?= (int) $result['total_scenarios']; ?></strong>
        </p>
        <p class="mb-4">
            <span class="badge <?= $result['passed'] ? 'text-bg-success' : 'text-bg-secondary'; ?> fs-6">
                <?= $result['passed'] ? 'Passed' : 'Failed'; ?>
            </span>
            <span class="text-body-secondary ms-2">Score: <?= (int) $result['score']; ?>%</span>
        </p>

        <h3 class="h5 fw-bold mb-3">Lessons Learned</h3>
        <ol class="list-group list-group-numbered mb-4">
            <?php foreach ($result['decisions'] as $decision): ?>
                <?php
                $decisionExplanation = htmlspecialchars((string) ($decision['explanation'] ?? ''), ENT_QUOTES, 'UTF-8');
                $decisionNumber = (int) ($decision['step_order'] ?? 0);
                ?>
                <li class="list-group-item px-3 py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                        <strong>Scenario <?= $decisionNumber; ?></strong>
                        <span class="badge <?= $decision['correct'] ? 'text-bg-success' : 'text-bg-warning'; ?>">
                            <?= $decision['correct'] ? 'Correct decision' : 'Review this decision'; ?>
                        </span>
                    </div>
                    <p class="mb-0"><?= nl2br($decisionExplanation); ?></p>
                </li>
            <?php endforeach; ?>
        </ol>

        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="?page=simulation&amp;id=<?= (int) $simulationId; ?>&amp;restart=1">
                Try Again
            </a>
            <a class="btn btn-outline-secondary" href="?page=simulations">Back to Simulations</a>
        </div>
    </section>
<?php elseif (!is_array($currentScenario)): ?>
    <div class="alert alert-light border" role="status">
        No scenario is available for this simulation yet.
    </div>
<?php else: ?>
    <?php
    $stepOrder = (int) ($currentScenario['step_order'] ?? ($currentStepIndex + 1));
    $scenarioId = (int) ($currentScenario['id'] ?? 0);
    $scenarioText = htmlspecialchars((string) ($currentScenario['scenario_text'] ?? ''), ENT_QUOTES, 'UTF-8');
    $progressPercent = (int) round(($stepOrder / 5) * 100);
    ?>
    <section aria-labelledby="simulation-progress-title" class="mb-4">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
            <h2 id="simulation-progress-title" class="h6 fw-bold mb-0">Scenario <?= $stepOrder; ?> of 5</h2>
            <span class="small text-body-secondary"><?= $progressPercent; ?>%</span>
        </div>
        <div class="progress" role="progressbar" aria-label="Simulation progress" aria-valuenow="<?= $stepOrder; ?>"
            aria-valuemin="1" aria-valuemax="5">
            <div class="progress-bar" style="width: <?= $progressPercent; ?>%"></div>
        </div>
    </section>

    <form method="POST" action="?page=simulation&amp;id=<?= (int) $simulationId; ?>">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(CsrfService::generateToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="step_order" value="<?= $stepOrder; ?>">
        <input type="hidden" name="scenario_id" value="<?= $scenarioId; ?>">

        <section class="card dashboard-stat mb-4 shadow-sm" aria-labelledby="scenario-title">
            <div class="card-body p-4 p-lg-5">
                <p class="small fw-bold text-primary text-uppercase mb-3">Review the situation</p>
                <div id="scenario-title" class="fs-5 mb-4"><?= nl2br($scenarioText); ?></div>

                <fieldset>
                    <legend class="h5 fw-bold mb-3">Is this phishing?</legend>

                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <div class="form-check border rounded-2 px-5 py-3 flex-fill">
                            <input class="form-check-input" type="radio" name="answer" id="answer-phishing" value="phishing"
                                required>
                            <label class="form-check-label w-100 fw-semibold" for="answer-phishing">
                                Phishing
                            </label>
                        </div>

                        <div class="form-check border rounded-2 px-5 py-3 flex-fill">
                            <input class="form-check-input" type="radio" name="answer" id="answer-legitimate"
                                value="legitimate" required>
                            <label class="form-check-label w-100 fw-semibold" for="answer-legitimate">
                                Legitimate
                            </label>
                        </div>
                    </div>
                </fieldset>
            </div>
        </section>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <button class="btn btn-primary btn-lg" type="submit">
                <?= $stepOrder === 5 ? 'Complete Simulation' : 'Continue to Next Scenario'; ?>
            </button>
            <a class="link-secondary" href="?page=simulation&amp;id=<?= (int) $simulationId; ?>&amp;restart=1">
                Restart Simulation
            </a>
        </div>
    </form>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';