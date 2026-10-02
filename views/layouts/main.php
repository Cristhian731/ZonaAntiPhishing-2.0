<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars((string) ($title ?? 'Zona AntiPhishing'), ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --zap-primary: #1976D2;
            --zap-deep: #0D47A1;
            --zap-black: #000000;
            --bs-primary: var(--zap-primary);
            --bs-primary-rgb: 25, 118, 210;
            --bs-link-color: var(--zap-primary);
            --bs-link-hover-color: var(--zap-deep);
        }

        body {
            min-height: 100vh;
            background: #f4f7fb;
            color: var(--zap-black);
        }

        .site-navbar {
            background: var(--zap-deep);
            box-shadow: 0 2px 12px rgb(0 0 0 / 14%);
        }

        .brand-mark {
            display: inline-grid;
            width: 2rem;
            height: 2rem;
            place-items: center;
            border-radius: 0.375rem;
            background: var(--zap-black);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .navbar-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: 700;
        }

        .site-navbar .navbar-nav .nav-link {
            border-radius: 0.375rem;
            color: rgb(255 255 255 / 82%);
            padding: 0.55rem 0.75rem;
            transition: background-color 150ms ease, color 150ms ease;
        }

        .site-navbar .navbar-nav .nav-link:hover,
        .site-navbar .navbar-nav .nav-link:focus-visible,
        .site-navbar .navbar-nav .nav-link.active {
            background: rgb(255 255 255 / 13%);
            color: #fff;
        }

        .site-navbar .navbar-nav .nav-link-logout {
            color: #fff;
        }

        .dashboard-stat {
            height: 100%;
            border: 0;
            border-top: 4px solid var(--zap-primary);
            border-radius: 0.5rem;
        }

        .dashboard-stat.stat-deep {
            border-top-color: var(--zap-deep);
        }

        .dashboard-stat.stat-black {
            border-top-color: var(--zap-black);
        }

        .dashboard-stat-value {
            color: var(--zap-deep);
            font-size: 2rem;
            font-weight: 750;
            line-height: 1.1;
        }

        .profile-panel {
            border-left: 4px solid var(--zap-deep);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark site-navbar">
        <div class="container-xl">
            <a class="navbar-brand" href="?page=dashboard#dashboard-top">
                <span class="brand-mark" aria-hidden="true">ZA</span>
                <span>Zona AntiPhishing</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation"
                aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavigation">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 py-2 py-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? 'dashboard') === 'dashboard' ? 'active' : ''; ?>"
                            aria-current="<?= ($activePage ?? 'dashboard') === 'dashboard' ? 'page' : 'false'; ?>"
                            href="?page=dashboard#dashboard-top">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') === 'courses' ? 'active' : ''; ?>"
                            aria-current="<?= ($activePage ?? '') === 'courses' ? 'page' : 'false'; ?>"
                            href="?page=courses">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=dashboard#quizzes">Quizzes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') === 'simulations' ? 'active' : ''; ?>"
                            aria-current="<?= ($activePage ?? '') === 'simulations' ? 'page' : 'false'; ?>"
                            href="?page=simulations">Simulations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') === 'certificates' ? 'active' : ''; ?>"
                            aria-current="<?= ($activePage ?? '') === 'certificates' ? 'page' : 'false'; ?>"
                            href="?page=certificates">Certificates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') === 'profile' ? 'active' : ''; ?>"
                            aria-current="<?= ($activePage ?? '') === 'profile' ? 'page' : 'false'; ?>"
                            href="?page=profile">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-xl py-4 py-lg-5">
        <?= $content ?? ''; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>