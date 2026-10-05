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

<header class="simulation-library-hero p-4 p-lg-5 mb-4 mb-lg-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div class="simulation-library-copy">
            <p class="small fw-bold text-primary text-uppercase mb-2">Practice lab / Training scenarios</p>
            <h1 class="display-5 fw-bold mb-2">Simulations</h1>
            <p class="text-body-secondary mb-0">
                Step into realistic situations, assess the signals, and build confidence in your decisions.
            </p>
        </div>
        <div class="simulation-library-count">
            <span class="simulation-card-icon" aria-hidden="true"></span>
            <span class="small fw-bold text-uppercase">Available training</span>
            <strong><?= count($simulations); ?></strong>
            <span><?= count($simulations) === 1 ? 'simulation' : 'simulations'; ?></span>
        </div>
    </div>
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
                <article class="card dashboard-stat simulation-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="simulation-card-mark d-flex justify-content-between align-items-start gap-3 mb-4">
                            <span class="simulation-card-icon" aria-hidden="true"></span>
                            <span class="badge text-bg-primary flex-shrink-0"><?= $difficulty; ?></span>
                        </div>
                        <p class="small fw-bold text-primary text-uppercase mb-2">Decision practice</p>
                        <h2 class="h4 fw-bold mb-3"><?= $simulationTitle; ?></h2>
                        <p class="card-text text-body-secondary mb-0 simulation-card-description">
                            <?= $simulationDescription !== '' ? $simulationDescription : 'No description available.'; ?>
                        </p>
                        <div class="mt-auto pt-4">
                            <a class="btn btn-primary simulation-card-cta"
                                href="?page=simulation&amp;id=<?= $simulationId; ?>">
                                <span>Enter training</span><span aria-hidden="true">&rarr;</span>
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
