<?php

require_once __DIR__ . '/../../app/controllers/AuthController.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $auth = new AuthController();

    $result = $auth->register(
        $_POST['name'],
        $_POST['email'],
        $_POST['password']
    );

    if ($result) {
        $message = "User registered successfully ✅";
    } else {
        $message = "Email already exists ❌";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Zona AntiPhishing</title>
</head>

<body>

    <h1>Zona AntiPhishing</h1>

    <h2>Register</h2>

    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">

        <label for="name">Name</label>
        <br>
        <input type="text" id="name" name="name" required>

        <br><br>

        <label for="email">Email</label>
        <br>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password</label>
        <br>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">
            Register
        </button>

    </form>

</body>

</html>