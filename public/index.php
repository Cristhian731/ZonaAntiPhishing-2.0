<?php

$isHttps = (
    isset($_SERVER['HTTPS'])
    && $_SERVER['HTTPS'] !== ''
    && strtolower((string) $_SERVER['HTTPS']) !== 'off'
) || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443');

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header(
    "Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'self'; "
    . "form-action 'self'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; "
    . "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; "
    . "img-src 'self' data: https://images.unsplash.com; "
    . "font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self'"
);

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$page = $_GET['page'] ?? (isset($_SESSION['user_id']) ? 'dashboard' : 'home');

if (!is_string($page)) {
    $page = 'login';
}

switch ($page) {

    case 'home':
        require_once __DIR__ . '/../views/home/index.php';
        break;

    case 'privacy-policy':
    case 'terms':
        $legalDocument = $page;
        require_once __DIR__ . '/../views/legal.php';
        break;

    case 'login':
        require_once __DIR__ . '/../views/auth/login.php';
        break;

    case 'register':
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    case 'dashboard':
        require_once __DIR__ . '/../views/dashboard/index.php';
        break;

    case 'courses':
        require_once __DIR__ . '/../views/courses/index.php';
        break;

    case 'course':
        require_once __DIR__ . '/../views/courses/show.php';
        break;

    case 'lesson':
        require_once __DIR__ . '/../views/lessons/show.php';
        break;

    case 'quiz':
        require_once __DIR__ . '/../views/quizzes/show.php';
        break;

    case 'quizzes':
        require_once __DIR__ . '/../views/quizzes/index.php';
        break;

    case 'simulations':
        require_once __DIR__ . '/../views/simulations/index.php';
        break;

    case 'certificates':
        require_once __DIR__ . '/../views/certificates/index.php';
        break;

    case 'certificate':
        require_once __DIR__ . '/../views/certificates/show.php';
        break;

    case 'certificate-pdf':
        require_once __DIR__ . '/../app/controllers/CertificatePdfController.php';
        (new CertificatePdfController())->download();
        break;

    case 'url-analyzer':
        require_once __DIR__ . '/../views/url-analyzer/index.php';
        break;

    case 'simulation':
        require_once __DIR__ . '/../views/simulations/show.php';
        break;

    case 'profile':
        require_once __DIR__ . '/../views/profile/index.php';
        break;

    case 'logout':
        require_once __DIR__ . '/../views/auth/logout.php';
        break;

    default:
        require_once __DIR__ . '/../views/auth/login.php';
        break;
}