<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$certificateId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($certificateId === false || $certificateId === null || $certificateId < 1) {
    header('Location: ?page=certificates');
    exit;
}

require_once __DIR__ . '/../../app/controllers/CertificateController.php';

$certificateController = new CertificateController();
$certificate = $certificateController->getCertificate($certificateId);

if ($certificate === null || (int) $certificate['user_id'] !== (int) $_SESSION['user_id']) {
    header('Location: ?page=certificates');
    exit;
}

$issuedTimestamp = strtotime((string) ($certificate['issued_at'] ?? ''));
$issuedDate = $issuedTimestamp !== false
    ? date('F j, Y', $issuedTimestamp)
    : (string) ($certificate['issued_at'] ?? '');

$title = 'Certificate | Zona AntiPhishing';
$activePage = 'certificates';

ob_start();
?>

<style>
    .certificate-actions {
        max-width: 960px;
        margin: 0 auto 1.5rem;
    }

    .academic-certificate {
        position: relative;
        display: flex;
        min-height: 590px;
        max-width: 960px;
        flex-direction: column;
        justify-content: space-between;
        margin: 0 auto;
        padding: 3.5rem 4rem 2.5rem;
        overflow: hidden;
        border: 1px solid var(--zap-achievement);
        outline: 1px solid var(--zap-border);
        outline-offset: -0.5rem;
        background: linear-gradient(135deg, var(--zap-canvas) 0%, var(--zap-surface) 48%, #f5f8fb 100%);
        box-shadow: 0 1.25rem 3rem rgb(13 71 161 / 10%);
        color: var(--zap-ink);
        text-align: center;
    }

    .academic-certificate> :not(.academic-certificate-watermark, .academic-certificate-decoration) {
        position: relative;
        z-index: 1;
    }

    .academic-certificate-watermark {
        position: absolute;
        z-index: 0;
        top: 50%;
        left: 50%;
        color: var(--zap-deep);
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 17rem;
        font-weight: 750;
        line-height: 1;
        opacity: 0.055;
        pointer-events: none;
        transform: translate(-50%, -54%);
        user-select: none;
    }

    .academic-certificate-decoration {
        position: absolute;
        z-index: 1;
        right: 12%;
        left: 12%;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--zap-achievement) 18%, var(--zap-achievement) 82%, transparent);
    }

    .academic-certificate-decoration-top {
        top: 2.25rem;
    }

    .academic-certificate-decoration-bottom {
        bottom: 2.25rem;
    }

    .academic-certificate::before,
    .academic-certificate::after {
        position: absolute;
        width: 3.25rem;
        height: 3.25rem;
        border-color: var(--zap-achievement);
        border-style: solid;
        content: '';
    }

    .academic-certificate::before {
        top: 1rem;
        left: 1rem;
        border-width: 1px 0 0 1px;
    }

    .academic-certificate::after {
        right: 1rem;
        bottom: 1rem;
        border-width: 0 1px 1px 0;
    }

    .academic-certificate-header {
        color: var(--zap-deep);
        font-size: 1rem;
        font-weight: 750;
        text-transform: uppercase;
    }

    .academic-certificate-subtitle {
        color: #5d6e7c;
        font-size: 0.875rem;
    }

    .academic-certificate-rule {
        width: 5rem;
        height: 2px;
        margin: 1.5rem auto 1.75rem;
        background: var(--zap-achievement);
    }

    .academic-certificate-title {
        color: #102f4a;
        font-size: 2rem;
        font-weight: 750;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .academic-certificate-recipient-label {
        margin-bottom: 0.45rem;
        color: var(--zap-muted);
    }

    .academic-certificate-recipient {
        margin-bottom: 1rem;
        color: var(--zap-deep);
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 2.75rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .academic-certificate-course-label {
        margin-bottom: 0.25rem;
        color: var(--zap-muted);
    }

    .academic-certificate-course {
        color: var(--zap-deep);
        font-size: 1.55rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .academic-certificate-seal {
        display: inline-grid;
        width: 9.45rem;
        height: 9.45rem;
        place-content: center;
        margin: 1.25rem auto 0;
        padding: 1.25rem;
        border: 1px solid var(--zap-achievement);
        border-radius: 50%;
        color: #806321;
        font-size: 0.72rem;
        font-weight: 750;
        line-height: 1.35;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        text-align: center;
    }

    .academic-certificate-footer {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        align-items: end;
        gap: 1rem;
        border-top: 1px solid #dbe2e8;
        padding-top: 1.25rem;
        text-align: left;
    }

    .academic-certificate-meta-label {
        display: block;
        margin-bottom: 0.3rem;
        color: var(--zap-muted);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .academic-certificate-signature {
        text-align: center;
    }

    .academic-certificate-signature-rule {
        width: 100%;
        max-width: 13rem;
        margin: 0 auto 0.5rem;
        border-top: 1px solid var(--zap-muted);
    }

    @media (max-width: 700px) {
        .academic-certificate {
            min-height: 0;
            padding: 2.5rem 1.75rem 2rem;
        }

        .academic-certificate-watermark {
            font-size: 12rem;
        }

        .academic-certificate-title {
            font-size: 1.5rem;
        }

        .academic-certificate-recipient {
            font-size: 2rem;
        }

        .academic-certificate-course {
            font-size: 1.25rem;
        }

        .academic-certificate-footer {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .academic-certificate-seal {
            width: 9rem;
            height: 9rem;
        }
    }

    @media print {
        body {
            background: #fff;
        }

        .site-navbar,
        .certificate-actions,
        footer {
            display: none !important;
        }

        main.container-xl {
            max-width: none;
            padding: 0 !important;
        }

        .academic-certificate {
            min-height: 185mm;
            box-shadow: none;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<div class="certificate-actions d-flex flex-wrap gap-2">
    <a class="btn btn-outline-secondary" href="?page=certificates">&larr; Certificates</a>
    <a class="btn btn-achievement ms-2" href="?page=certificate-pdf&amp;id=<?= (int) $certificate['id']; ?>">
        Download PDF
    </a>
</div>

<article class="academic-certificate" aria-labelledby="certificate-title">
    <span class="academic-certificate-watermark" aria-hidden="true">ZA</span>
    <span class="academic-certificate-decoration academic-certificate-decoration-top" aria-hidden="true"></span>
    <span class="academic-certificate-decoration academic-certificate-decoration-bottom" aria-hidden="true"></span>
    <header>
        <p class="academic-certificate-header mb-1">Zona AntiPhishing</p>
        <p class="academic-certificate-subtitle mb-0">Cybersecurity Awareness Platform</p>
        <div class="academic-certificate-rule" aria-hidden="true"></div>
        <h1 id="certificate-title" class="academic-certificate-title mb-4">Certificate of Completion</h1>
    </header>

    <div>
        <p class="academic-certificate-recipient-label">This certificate is proudly awarded to</p>
        <p class="academic-certificate-recipient">
            <?= htmlspecialchars((string) $certificate['user_name'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <p class="mb-2">For successfully completing the course</p>
        <p class="academic-certificate-course mb-3">
            <?= htmlspecialchars((string) $certificate['course_title'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <p class="text-body-secondary mx-auto mb-0" style="max-width: 38rem;">
            and demonstrating knowledge in phishing awareness, prevention and digital security best practices.
        </p>
        <div class="academic-certificate-seal" aria-label="Verified completion">
            <span>Verified</span>
            <span>Completion</span>
        </div>
    </div>

    <footer class="academic-certificate-footer">
        <div>
            <span class="academic-certificate-meta-label">Issue Date</span>
            <span><?= htmlspecialchars($issuedDate, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <div>
            <span class="academic-certificate-meta-label">Certificate ID</span>
            <span><?= htmlspecialchars((string) $certificate['certificate_code'], ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <div class="academic-certificate-signature">
            <div class="academic-certificate-signature-rule" aria-hidden="true"></div>
            <strong class="d-block">Zona AntiPhishing Team</strong>
            <span class="small text-body-secondary">Educational Security Program</span>
        </div>
    </footer>
</article>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';