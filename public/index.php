<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$page = $_GET['page'] ?? 'login';

if (!is_string($page)) {
    $page = 'login';
}

switch ($page) {

    case 'register':
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    case 'dashboard':
        require_once __DIR__ . '/../views/dashboard/index.php';
        break;

    case 'logout':
        require_once __DIR__ . '/../views/auth/logout.php';
        break;

    default:
        require_once __DIR__ . '/../views/auth/login.php';
        break;
}