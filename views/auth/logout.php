<?php

require_once __DIR__ . '/../../app/services/CsrfService.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Logout requests must use POST.';
    exit;
}

$csrfToken = isset($_POST['csrf_token']) && is_string($_POST['csrf_token'])
    ? $_POST['csrf_token']
    : null;

if (!CsrfService::validateToken($csrfToken)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'The logout request could not be verified. Please try again.';
    exit;
}

$_SESSION = [];

$cookieParams = session_get_cookie_params();

if (ini_get('session.use_cookies')) {
    setcookie(
        session_name(),
        '',
        [
            'expires' => time() - 42000,
            'path' => $cookieParams['path'],
            'domain' => $cookieParams['domain'],
            'secure' => $cookieParams['secure'],
            'httponly' => $cookieParams['httponly'],
            'samesite' => $cookieParams['samesite'] ?? 'Lax',
        ]
    );
}

session_destroy();

header('Location: ?page=login');
exit;