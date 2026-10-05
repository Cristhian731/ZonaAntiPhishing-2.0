<?php

require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/services/CsrfService.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = isset($_POST['csrf_token']) && is_string($_POST['csrf_token'])
        ? $_POST['csrf_token']
        : null;

    if (!CsrfService::validateToken($csrfToken)) {
        $message = 'Your session expired or the request could not be verified. Please try again.';
    } elseif (
        !isset($_POST['accept_privacy'], $_POST['accept_terms'])
        || $_POST['accept_privacy'] !== '1'
        || $_POST['accept_terms'] !== '1'
    ) {
        $message = 'Please accept the Privacy Policy and Terms of Service to create an account.';
    } elseif (!isset($_POST['name'], $_POST['email'], $_POST['password'])) {
        $message = 'Please complete all required fields.';
    } elseif (
        !is_string($_POST['name'])
        || !is_string($_POST['email'])
        || !is_string($_POST['password'])
    ) {
        $message = 'Please enter valid values for all required fields.';
    } else {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if ($name === '' || $email === '' || $password === '') {
            $message = 'Name, email, and password are required.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $message = 'Please enter a valid email address.';
        } elseif (!AuthController::hasValidPasswordLength($password)) {
            $message = 'Your password must be at least '
                . AuthController::MIN_PASSWORD_LENGTH
                . ' characters long.';
        } else {
            $auth = new AuthController();
            $result = $auth->register($name, $email, $password);

            if ($result) {
                $message = "User registered successfully ✅";
            } else {
                $message = "Email already exists ❌";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Zona AntiPhishing</title>
    <meta name="theme-color" content="#123a37">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --ink: #173436;
            --muted: #546768;
            --paper: #f4f6f1;
            --white: #fff;
            --green: #1e6559;
            --green-deep: #123a37;
            --coral: #e66e50;
            --gold: #edbd59;
        }

        body {
            min-height: 100vh;
            background: var(--paper);
            color: var(--ink);
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
        }

        .auth-layout {
            display: grid;
            min-height: 100vh;
            grid-template-columns: minmax(0, 1.05fr) minmax(420px, 0.95fr);
        }

        .auth-aside {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            background: var(--green-deep);
            color: var(--white);
            padding: 2.5rem clamp(2rem, 5vw, 5.5rem);
        }

        .auth-aside::before,
        .auth-aside::after {
            position: absolute;
            inset: 0;
            content: "";
        }

        .auth-aside::before {
            background-image: url("https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1800&q=85");
            background-position: center;
            background-size: cover;
        }

        .auth-aside::after {
            background: rgb(18 58 55 / 84%);
        }

        .auth-brand,
        .auth-aside-content,
        .auth-aside-footer {
            position: relative;
            z-index: 1;
        }

        .auth-brand {
            color: var(--white);
            font-size: 1.05rem;
            font-weight: 800;
            text-decoration: none;
        }

        .brand-mark {
            display: inline-grid;
            width: 2.15rem;
            height: 2.15rem;
            place-items: center;
            margin-right: 0.55rem;
            border-radius: 0.35rem;
            background: var(--coral);
            color: var(--ink);
            font-size: 0.72rem;
            vertical-align: middle;
        }

        .auth-eyebrow {
            color: var(--gold);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .auth-aside h2 {
            max-width: 570px;
            font-size: 3.25rem;
            font-weight: 850;
            line-height: 1.04;
        }

        .auth-aside-copy {
            max-width: 520px;
            color: rgb(255 255 255 / 84%);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .auth-sequence {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 750;
        }

        .auth-sequence span {
            border: 1px solid rgb(255 255 255 / 35%);
            border-radius: 0.3rem;
            padding: 0.45rem 0.65rem;
        }

        .auth-aside-footer {
            color: rgb(255 255 255 / 72%);
            font-size: 0.88rem;
        }

        .auth-main {
            display: grid;
            min-height: 100vh;
            align-items: center;
            justify-items: center;
            padding: 2.5rem clamp(1.25rem, 4vw, 4rem);
        }

        .auth-form-wrap {
            width: 100%;
            max-width: 470px;
        }

        .back-link,
        .auth-switch a {
            color: var(--green);
            font-weight: 700;
            text-decoration-thickness: 1px;
            text-underline-offset: 0.2em;
        }

        .back-link:hover,
        .auth-switch a:hover {
            color: var(--green-deep);
        }

        .auth-main h1 {
            margin-top: 2.4rem;
            font-size: 2.45rem;
            font-weight: 850;
            line-height: 1.1;
        }

        .auth-subtitle {
            color: var(--muted);
            font-size: 1.02rem;
            line-height: 1.6;
        }

        .form-label {
            color: var(--ink);
            font-size: 0.92rem;
            font-weight: 700;
        }

        .form-control {
            min-height: 3.1rem;
            border-color: #c9d4ce;
            border-radius: 0.35rem;
            background: var(--white);
        }

        .form-control:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 0.2rem rgb(30 101 89 / 15%);
        }

        .policy-consent {
            padding: 0.85rem 0 0;
            border: 0;
            border-top: 1px solid #d8e0d9;
        }

        .policy-consent .form-check {
            display: flex;
            align-items: flex-start;
            gap: 0.35rem;
            padding-left: 1.5rem;
        }

        .policy-consent .form-check-input {
            flex: 0 0 auto;
            margin-top: 0.28rem;
            margin-left: -1.5rem;
        }

        .policy-consent .form-check-label {
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .policy-consent a {
            color: var(--green);
            font-weight: 700;
            text-underline-offset: 0.15em;
        }

        .btn-auth {
            min-height: 3.1rem;
            border: 1px solid var(--coral);
            border-radius: 0.35rem;
            background: var(--coral);
            color: #201c19;
            font-weight: 800;
        }

        .btn-auth:hover,
        .btn-auth:focus-visible {
            border-color: #cf563a;
            background: #cf563a;
            color: var(--white);
        }

        .auth-switch {
            color: var(--muted);
        }

        @media (max-width: 767.98px) {
            .auth-layout {
                grid-template-columns: 1fr;
            }

            .auth-aside {
                min-height: auto;
                gap: 2.5rem;
                padding: 1.3rem 1.25rem 1.5rem;
            }

            .auth-aside h2 {
                margin-bottom: 0.5rem;
                font-size: 1.65rem;
            }

            .auth-aside-copy,
            .auth-sequence,
            .auth-aside-footer {
                display: none;
            }

            .auth-main {
                min-height: auto;
                padding: 2rem 1.25rem 3rem;
            }

            .auth-main h1 {
                margin-top: 2rem;
                font-size: 2.1rem;
            }
        }
    </style>
</head>

<body>
    <main class="auth-layout">
        <aside class="auth-aside">
            <a class="auth-brand" href="?page=home" aria-label="Zona AntiPhishing home">
                <span class="brand-mark" aria-hidden="true">ZA</span>Zona AntiPhishing
            </a>
            <div class="auth-aside-content">
                <p class="auth-eyebrow mb-3">Practical cybersecurity education</p>
                <h2>Learn. Practice. Analyze. Protect.</h2>
                <p class="auth-aside-copy mb-4">
                    Build practical skills to recognize phishing through interactive learning and realistic practice.
                </p>
                <div class="auth-sequence" aria-label="Learn, Practice, Analyze, Certify">
                    <span>Learn</span><span>Practice</span><span>Analyze</span><span>Certify</span>
                </div>
            </div>
            <p class="auth-aside-footer mb-0">Make safer decisions, one message at a time.</p>
        </aside>

        <section class="auth-main" aria-labelledby="register-title">
            <div class="auth-form-wrap">
                <a class="back-link" href="?page=home">Back to Home</a>

                <h1 id="register-title">Create Your Account</h1>
                <p class="auth-subtitle mb-4">Start learning how to recognize and prevent phishing attacks.</p>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-light border" role="status">
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="?page=register">
                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars(CsrfService::generateToken(), ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" type="text" id="name" name="name" autocomplete="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" id="email" name="email" autocomplete="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" type="password" id="password" name="password"
                            autocomplete="new-password" minlength="<?= AuthController::MIN_PASSWORD_LENGTH; ?>" required>
                    </div>

                    <fieldset class="policy-consent mb-4">
                        <legend class="visually-hidden">Required agreements</legend>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="accept-privacy"
                                name="accept_privacy" value="1" required>
                            <label class="form-check-label" for="accept-privacy">
                                I have read and accept the
                                <a href="?page=privacy-policy" target="_blank" rel="noopener">Privacy Policy</a>.
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="accept-terms"
                                name="accept_terms" value="1" required>
                            <label class="form-check-label" for="accept-terms">
                                I have read and accept the
                                <a href="?page=terms" target="_blank" rel="noopener">Terms of Service</a>.
                            </label>
                        </div>
                    </fieldset>

                    <button class="btn btn-auth w-100" type="submit">Register</button>
                </form>

                <p class="auth-switch mt-4 mb-0">
                    Already have an account? <a href="?page=login">Login</a>
                </p>
            </div>
        </section>
    </main>
</body>

</html>