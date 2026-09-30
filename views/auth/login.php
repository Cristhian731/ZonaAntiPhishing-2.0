<?php

require_once __DIR__ . '/../../app/controllers/AuthController.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$message = '';

if (isset($_SESSION['user_id'])) {
    header('Location: ?page=dashboard');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (is_string($email) && is_string($password) && trim($email) !== '' && $password !== '') {
        $auth = new AuthController();
        $result = $auth->login(trim($email), $password);

        if ($result) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $result['id'];
            $_SESSION['user_name'] = $result['name'];
            $_SESSION['user_role'] = $result['role'] ?? '';

            header('Location: ?page=dashboard');
            exit;
        }

        $message = 'Invalid credentials.';
    } else {
        $message = 'Invalid credentials.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zona AntiPhishing</title>
</head>

<body>

    <h1>Zona AntiPhishing</h1>

    <h2>Login</h2>

    <?php if (!empty($message)): ?>
        <p>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="?page=login">

        <label for="email">Email</label>
        <br>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password</label>
        <br>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">
            Login
        </button>

    </form>

</body>

</html>