<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>

    <h1>Zona AntiPhishing Dashboard</h1>

    <p>
        Welcome,
        <?= htmlspecialchars((string) ($_SESSION['user_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <p>
        Role:
        <?= htmlspecialchars((string) ($_SESSION['user_role'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <p>
        <a href="?page=logout">Logout</a>
    </p>
</body>

</html>