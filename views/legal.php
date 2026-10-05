<?php

$documents = [
    'privacy-policy' => [
        'title' => 'Privacy Policy',
        'intro' => 'This notice explains what information Zona AntiPhishing uses to provide its educational services.',
        'sections' => [
            [
                'title' => 'Information we collect',
                'body' => 'When you create an account, the platform stores your name, email address, and a protected password hash for account access. A session cookie and session data are used to keep requests associated with your visit and protect form submissions. As you use the platform, it records learning activity such as completed lessons, quiz and simulation progress, and certificates earned.',
            ],
            [
                'title' => 'Why we use it',
                'body' => 'Account information is used to create and authenticate your account. Learning activity is used to show your progress, continue your learning path, and determine when a course-completion certificate is available.',
            ],
            [
                'title' => 'Progress and certificates',
                'body' => 'Progress records are associated with your account so the platform can display completed lessons and learning results. Certificates are generated from account and course-completion information, including the learner name, course, and issue details.',
            ],
            [
                'title' => 'Your account',
                'body' => 'Use an email address you control and keep your sign-in credentials private. The platform does not need payment card details to provide its currently available learning features. Do not enter passwords, confidential messages, or other sensitive personal information into exercises or URL analysis. Some pages load Bootstrap assets from jsDelivr and imagery from Unsplash, so visiting those pages makes requests to those providers subject to their own privacy practices.',
            ],
            [
                'title' => 'Educational and security limits',
                'body' => 'Zona AntiPhishing is an educational platform. Its lessons, simulations, certificates, and URL analysis are intended for learning and do not guarantee that a message, link, or website is safe.',
            ],
        ],
    ],
    'terms' => [
        'title' => 'Terms of Service',
        'intro' => 'By creating an account or using Zona AntiPhishing, you agree to use the platform responsibly for educational purposes.',
        'sections' => [
            [
                'title' => 'Educational use',
                'body' => 'The courses, quizzes, simulations, URL analyzer, and certificates are provided for cybersecurity awareness and learning. Use simulations and analysis only for lawful, educational purposes.',
            ],
            [
                'title' => 'Your responsibilities',
                'body' => 'Provide accurate account information, protect your credentials, and use the platform in a way that respects other people and applicable laws. Do not use it to access accounts or systems without authorization, distribute harmful content, or submit confidential information.',
            ],
            [
                'title' => 'Platform limitations',
                'body' => 'Educational content and automated URL analysis may be incomplete or out of date. A low-risk result is not proof that a link or website is safe, and the platform is not a substitute for professional security tools, advice, or incident response.',
            ],
            [
                'title' => 'Certificates',
                'body' => 'A certificate represents an educational achievement recorded on this platform. It is not a professional license, accredited qualification, independent identity verification, or guarantee of cybersecurity expertise.',
            ],
            [
                'title' => 'Availability',
                'body' => 'Features and learning content may change as the platform develops. Premium and Business plans shown on the Landing Page are roadmap concepts, not currently available paid services or offers to purchase.',
            ],
        ],
    ],
];

$document = $documents[$legalDocument] ?? $documents['privacy-policy'];
$title = $document['title'] . ' | Zona AntiPhishing';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123a37">
    <meta name="description" content="<?= htmlspecialchars($document['intro'], ENT_QUOTES, 'UTF-8'); ?>">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --ink: #173436;
            --muted: #546768;
            --paper: #f4f6f1;
            --surface: #fbfaf5;
            --green: #1e6559;
            --green-deep: #123a37;
            --coral: #e66e50;
            --line: #d8e0d9;
        }

        body {
            min-height: 100vh;
            background: var(--paper);
            color: var(--ink);
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
        }

        .legal-nav,
        .legal-footer {
            background: var(--green-deep);
            color: rgb(255 255 255 / 84%);
        }

        .legal-nav a,
        .legal-footer a {
            color: #fff;
        }

        .legal-brand {
            font-weight: 800;
            text-decoration: none;
        }

        .legal-main {
            max-width: 860px;
            padding-top: 4rem;
            padding-bottom: 4rem;
        }

        .legal-content {
            padding: clamp(1.25rem, 4vw, 2.5rem);
            border: 1px solid var(--line);
            border-radius: 0.5rem;
            background: var(--surface);
            box-shadow: 0 0.35rem 1.1rem rgb(18 58 55 / 8%);
        }

        .legal-kicker {
            color: var(--green);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .legal-intro,
        .legal-content section p {
            color: var(--muted);
            line-height: 1.75;
        }

        .legal-content section + section {
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--line);
        }

        .legal-content h2 {
            font-size: 1.2rem;
            font-weight: 750;
        }

        .legal-content section p {
            margin-bottom: 0;
        }

        .legal-footer a:hover,
        .legal-footer a:focus-visible {
            color: var(--coral);
        }

        @media (max-width: 575.98px) {
            .legal-main {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
        }
    </style>
</head>

<body>
    <header class="legal-nav py-3">
        <nav class="container-xl d-flex flex-wrap align-items-center justify-content-between gap-3"
            aria-label="Policy navigation">
            <a class="legal-brand" href="?page=home">Zona AntiPhishing</a>
            <div class="d-flex flex-wrap gap-3">
                <a href="?page=home">Home</a>
                <a href="?page=privacy-policy">Privacy Policy</a>
                <a href="?page=terms">Terms of Service</a>
            </div>
        </nav>
    </header>

    <main class="container-xl legal-main">
        <article class="legal-content">
            <p class="legal-kicker mb-2">Zona AntiPhishing · Transparency</p>
            <h1 class="display-6 fw-bold mb-3"><?= htmlspecialchars($document['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="legal-intro mb-4"><?= htmlspecialchars($document['intro'], ENT_QUOTES, 'UTF-8'); ?></p>

            <?php foreach ($document['sections'] as $section): ?>
                <section>
                    <h2><?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><?= htmlspecialchars($section['body'], ENT_QUOTES, 'UTF-8'); ?></p>
                </section>
            <?php endforeach; ?>
        </article>
    </main>

    <footer class="legal-footer py-3">
        <div class="container-xl d-flex flex-wrap justify-content-between gap-2">
            <span>Zona AntiPhishing · Educational platform</span>
            <a href="?page=register">Create an account</a>
        </div>
    </footer>
</body>

</html>
