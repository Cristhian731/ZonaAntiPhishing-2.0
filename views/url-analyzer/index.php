<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

require_once __DIR__ . '/../../app/controllers/UrlAnalyzerController.php';
require_once __DIR__ . '/../../app/services/CsrfService.php';

$title = 'URL Analyzer | Zona AntiPhishing';
$activePage = 'url-analyzer';
$inputUrl = isset($_POST['url']) && is_string($_POST['url']) ? $_POST['url'] : '';
$analysis = null;
$errorMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!CsrfService::validateToken($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your session expired or the request could not be verified. Please try again.';
    } else {
        $urlAnalyzerController = new UrlAnalyzerController();
        $analysis = $urlAnalyzerController->analyze($inputUrl);
    }
}

ob_start();
?>

<header class="mb-4 mb-lg-5">
    <p class="small fw-bold text-primary text-uppercase mb-2">Security Tool</p>
    <h1 class="display-6 fw-bold mb-2">URL Analyzer</h1>
</header>

<section class="mb-4" aria-labelledby="url-form-title">
    <h2 id="url-form-title" class="h5 fw-bold mb-3">Enter a URL to analyze</h2>
    <form method="POST" action="?page=url-analyzer">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(CsrfService::generateToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="input-group input-group-lg">
            <input class="form-control" type="text" id="url" name="url"
                value="<?= htmlspecialchars($inputUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://example.com"
                aria-label="Enter a URL to analyze" required>
            <button class="btn btn-primary" type="submit">Analyze</button>
        </div>
    </form>
</section>

<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-warning" role="alert">
        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (is_array($analysis)): ?>
    <?php
    $riskClass = match ($analysis['risk_level']) {
        'High' => 'danger',
        'Medium' => 'warning',
        default => 'success',
    };
    ?>
    <section class="card border-0 shadow-sm" aria-labelledby="analysis-result-title" aria-live="polite">
        <div class="card-body p-4">
            <h2 id="analysis-result-title" class="h4 fw-bold mb-3">Analysis Result</h2>

            <p class="mb-3">
                <strong>Risk Level:</strong>
                <span class="badge text-bg-<?= $riskClass; ?>">
                    <?= htmlspecialchars($analysis['risk_level'], ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </p>

            <h3 class="h6 fw-bold">Findings</h3>
            <?php if ($analysis['findings'] === []): ?>
                <p>No common warning signs were detected.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($analysis['findings'] as $finding): ?>
                        <li><?= htmlspecialchars($finding, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <h3 class="h6 fw-bold">Recommendation</h3>
            <p class="mb-0"><?= htmlspecialchars($analysis['recommendation'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </section>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';