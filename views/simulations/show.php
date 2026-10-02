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

$scenario = $simulationController->getScenario((int) $simulationId);
$simulationResultKey = 'simulation_result_' . (int) $simulationId;
$result = $_SESSION[$simulationResultKey] ?? null;
unset($_SESSION[$simulationResultKey]);
$errorMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!CsrfService::validateToken($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your session expired or the request could not be verified. Please try again.';
    } else {
        $answer = $_POST['answer'] ?? null;

        if (is_string($answer) && $scenario) {
            $result = $simulationController->submitSimulation(
                (int) $_SESSION['user_id'],
                (int) $simulationId,
                $answer
            );

            if ($result !== null) {
                $_SESSION[$simulationResultKey] = $result;
                header('Location: ?page=simulation&id=' . (int) $simulationId);
                exit;
            }
        }

        $errorMessage = 'Select a valid answer and try again.';
    }
}

$title = (string) ($simulation['title'] ?? 'Simulation') . ' | Zona AntiPhishing';
$activePage = 'simulations';
$simulationTitle = htmlspecialchars((string) ($simulation['title'] ?? 'Simulation'), ENT_QUOTES, 'UTF-8');
$scenarioText = htmlspecialchars((string) ($scenario['scenario_text'] ?? ''), ENT_QUOTES, 'UTF-8');
$explanation = htmlspecialchars((string) ($result['explanation'] ?? ''), ENT_QUOTES, 'UTF-8');

ob_start();
?>

<header class="mb-4 mb-lg-5">
    <a class="link-primary text-decoration-none fw-semibold" href="?page=simulations">&larr; Back to simulations</a>

    <div class="mt-4">
        <p class="small fw-bold text-primary text-uppercase mb-2">Simulation scenario</p>
        <h1 class="display-6 fw-bold mb-0"><?= $simulationTitle; ?></h1>
    </div>
</header>

<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-warning" role="alert">
        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!$scenario): ?>
    <div class="alert alert-light border" role="status">
        No scenario is available for this simulation yet.
    </div>
<?php elseif ($result !== null): ?>
    <section class="alert <?= $result['correct'] ? 'alert-success' : 'alert-danger'; ?>" role="status">
        <h2 class="h4 fw-bold"><?= $result['correct'] ? 'Correct' : 'Incorrect'; ?></h2>
        <?php if ($explanation !== ''): ?>
            <p class="mb-0"><?= nl2br($explanation); ?></p>
        <?php endif; ?>
    </section>
<?php else: ?>
    <form method="POST" action="?page=simulation&amp;id=<?= (int) $simulationId; ?>">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(CsrfService::generateToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <section class="card dashboard-stat mb-4 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <p class="small fw-bold text-primary text-uppercase mb-3">Review the scenario</p>
                <div class="fs-5 mb-4"><?= nl2br($scenarioText); ?></div>

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

        <button class="btn btn-primary btn-lg" type="submit">Submit Answer</button>
    </form>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
