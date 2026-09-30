<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

require_once __DIR__ . '/../../app/controllers/SimulationController.php';

$simulationController = new SimulationController();
$simulations = $simulationController->getSimulations();
$simulations = is_array($simulations) ? $simulations : [];

$title = 'Simulations | Zona AntiPhishing';
$activePage = 'simulations';

ob_start();
?>

<header class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4 mb-lg-5">
    <div>
        <p class="small fw-bold text-primary text-uppercase mb-2">Practice lab</p>
        <h1 class="display-6 fw-bold mb-2">Simulations</h1>
        <p class="text-body-secondary mb-0">Practice identifying suspicious messages and scenarios.</p>
    </div>

    <span class="badge rounded-pill text-bg-light border text-dark px-3 py-2">
        <?= count($simulations); ?> <?= count($simulations) === 1 ? 'simulation' : 'simulations'; ?>
    </span>
</header>

<?php if ($simulations === []): ?>
    <div class="alert alert-light border mb-0" role="status">
        No simulations are available yet.
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 g-lg-4">
        <?php foreach ($simulations as $simulation): ?>
            <?php
            $simulationId = (int) ($simulation['id'] ?? 0);
            $simulationTitle = htmlspecialchars((string) ($simulation['title'] ?? 'Untitled simulation'), ENT_QUOTES, 'UTF-8');
            $simulationDescription = htmlspecialchars((string) ($simulation['description'] ?? ''), ENT_QUOTES, 'UTF-8');
            $difficulty = htmlspecialchars(ucfirst((string) ($simulation['difficulty'] ?? 'beginner')), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="col">
                <article class="card dashboard-stat h-100 shadow-sm">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <h2 class="h5 fw-bold mb-0"><?= $simulationTitle; ?></h2>
                            <span class="badge text-bg-primary flex-shrink-0"><?= $difficulty; ?></span>
                        </div>
                        <p class="card-text text-body-secondary mb-0">
                            <?= $simulationDescription !== '' ? $simulationDescription : 'No description available.'; ?>
                        </p>
                        <div class="mt-auto pt-4">
                            <a class="btn btn-primary" href="?page=simulation&amp;id=<?= $simulationId; ?>">
                                Start Simulation
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
