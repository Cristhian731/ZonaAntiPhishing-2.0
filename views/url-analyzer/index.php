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

<header class="url-analyzer-hero p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
        <div class="url-analyzer-hero-copy">
            <p class="small fw-bold text-primary text-uppercase mb-2">Security tool / Link inspection</p>
            <h1 class="display-5 fw-bold mb-2">URL Analyzer</h1>
            <p class="text-body-secondary mb-0">
                Pause before you click. Inspect a link for common warning signs and learn what to check next.
            </p>
        </div>
        <span class="url-analyzer-hero-mark" aria-hidden="true">
            <span class="quick-action-icon quick-action-icon-search"></span>
        </span>
    </div>
</header>

<section class="url-analyzer-guidance mb-4" aria-labelledby="url-guidance-title">
    <div class="url-analyzer-guidance-heading">
        <p class="small fw-bold text-primary text-uppercase mb-2">Before you analyze</p>
        <h2 id="url-guidance-title" class="h5 fw-bold mb-0">A quick safety check</h2>
    </div>
    <div class="url-analyzer-guidance-points">
        <p><span class="url-guidance-number">01</span><span>Check the domain name carefully.</span></p>
        <p><span class="url-guidance-number">02</span><span>Watch for misspellings and extra subdomains.</span></p>
        <p><span class="url-guidance-number">03</span><span>Never enter sensitive details on an unexpected page.</span></p>
    </div>
</section>

<section class="url-analyzer-form-card mb-4" aria-labelledby="url-form-title">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <p class="small fw-bold text-primary text-uppercase mb-1">Inspect a link</p>
            <h2 id="url-form-title" class="h5 fw-bold mb-0">Enter a URL to analyze</h2>
        </div>
        <span class="small text-body-secondary">Use the full web address</span>
    </div>
    <form method="POST" action="?page=url-analyzer">
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars(CsrfService::generateToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="input-group input-group-lg url-analyzer-input-group">
            <input class="form-control" type="text" id="url" name="url"
                value="<?= htmlspecialchars($inputUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://example.com"
                aria-label="Enter a URL to analyze" required>
            <button class="btn btn-primary url-analyzer-submit" type="submit">Analyze URL <span aria-hidden="true">&rarr;</span></button>
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
    $riskVariant = strtolower((string) ($analysis['risk_level'] ?? 'low'));
    ?>
    <section class="risk-result risk-result-<?= htmlspecialchars($riskVariant, ENT_QUOTES, 'UTF-8'); ?> p-4 p-lg-5"
        aria-labelledby="analysis-result-title" aria-live="polite">
        <div class="risk-result-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <p class="small fw-bold text-primary text-uppercase mb-2">Assessment complete</p>
                <h2 id="analysis-result-title" class="h3 fw-bold mb-1">Analysis Result</h2>
                <p class="small text-body-secondary mb-0">Review the indicators and recommendation below.</p>
            </div>

            <div class="risk-result-level text-sm-end">
                <span class="small fw-bold text-uppercase d-block mb-2">Risk level</span>
                <span class="risk-level text-bg-<?= $riskClass; ?>">
                    <?= htmlspecialchars($analysis['risk_level'], ENT_QUOTES, 'UTF-8'); ?> Risk
                </span>
            </div>
        </div>

        <div class="row g-3 g-lg-4">
            <div class="col-12 col-lg-7">
                <div class="risk-findings-panel h-100">
                    <p class="small fw-bold text-primary text-uppercase mb-2">What we found</p>
                    <h3 class="h5 fw-bold mb-3">Findings</h3>
                    <?php if ($analysis['findings'] === []): ?>
                        <p class="text-body-secondary mb-0">No common warning signs were detected.</p>
                    <?php else: ?>
                        <ul class="risk-findings-list mb-0">
                            <?php foreach ($analysis['findings'] as $finding): ?>
                                <li><?= htmlspecialchars($finding, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="risk-recommendation h-100">
                    <p class="small fw-bold text-primary text-uppercase mb-2">What to do next</p>
                    <h3 class="h5 fw-bold mb-2">Recommendation</h3>
                    <p class="mb-0"><?= htmlspecialchars($analysis['recommendation'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';