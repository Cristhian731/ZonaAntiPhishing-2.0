<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$isAuthenticated = isset($_SESSION['user_id']);
$startLearningUrl = $isAuthenticated ? '?page=courses' : '?page=register';
$registerUrl = $isAuthenticated ? '?page=courses' : '?page=register';
$registerLabel = $isAuthenticated ? 'Explore Courses' : 'Register';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Learn to recognize phishing through interactive courses, realistic simulations, URL analysis and digital certificates.">
    <title>Zona AntiPhishing | Learn to recognize phishing</title>
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
            --line: #d8e0d9;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--paper);
            color: var(--ink);
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
        }

        .site-header {
            background: var(--paper);
        }

        .brand {
            color: var(--ink);
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
            background: var(--green-deep);
            color: var(--white);
            font-size: 0.72rem;
            vertical-align: middle;
        }

        .site-nav .nav-link {
            color: var(--ink);
            font-size: 0.93rem;
            font-weight: 650;
        }

        .btn-brand {
            border: 1px solid var(--coral);
            border-radius: 0.35rem;
            background: var(--coral);
            color: #201c19;
            font-weight: 750;
            padding: 0.72rem 1.05rem;
        }

        .btn-brand:hover,
        .btn-brand:focus-visible {
            border-color: #cf563a;
            background: #cf563a;
            color: var(--white);
        }

        .btn-quiet-light {
            border: 1px solid rgb(255 255 255 / 70%);
            border-radius: 0.35rem;
            color: var(--white);
            font-weight: 700;
            padding: 0.72rem 1.05rem;
        }

        .btn-quiet-light:hover,
        .btn-quiet-light:focus-visible {
            background: var(--white);
            color: var(--green-deep);
        }

        .hero {
            position: relative;
            display: flex;
            min-height: 34rem;
            align-items: center;
            overflow: hidden;
            background: var(--green-deep);
            color: var(--white);
        }

        .hero-image,
        .hero-scrim {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .hero-image {
            object-fit: cover;
            object-position: center 48%;
        }

        .hero-scrim {
            background: rgb(10 39 37 / 75%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 760px;
            padding-top: 3.5rem;
            padding-bottom: 3.5rem;
        }

        .eyebrow {
            color: var(--gold);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 730px;
            margin: 0 0 1.25rem;
            font-size: 3.35rem;
            font-weight: 850;
            line-height: 1.02;
        }

        .hero h1 span {
            display: block;
        }

        .hero-copy {
            max-width: 620px;
            color: rgb(255 255 255 / 91%);
            font-size: 1.12rem;
            line-height: 1.65;
        }

        .section-space {
            padding-top: 4.5rem;
            padding-bottom: 4.5rem;
        }

        .section-kicker {
            color: var(--green);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .section-heading {
            max-width: 800px;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.15;
        }

        .problem-section {
            background: var(--white);
        }

        .problem-copy {
            max-width: 850px;
            color: var(--muted);
            font-size: 1.22rem;
            line-height: 1.7;
        }

        .feature-card {
            height: 100%;
            border: 0;
            border-top: 4px solid var(--green);
            border-radius: 0.4rem;
            background: var(--white);
            box-shadow: 0 0.35rem 1.1rem rgb(23 52 54 / 7%);
        }

        .feature-card-practice {
            border-top-color: var(--coral);
        }

        .feature-card-analyze {
            border-top-color: var(--gold);
        }

        .feature-card-certify {
            border-top-color: var(--green-deep);
        }

        .feature-number {
            color: var(--green);
            font-size: 0.8rem;
            font-weight: 800;
        }

        .feature-card p,
        .audience-copy {
            color: var(--muted);
            line-height: 1.65;
        }

        .steps-section {
            background: var(--green-deep);
            color: var(--white);
        }

        .steps-section .section-kicker {
            color: var(--gold);
        }

        .steps-section .section-heading {
            color: var(--white);
        }

        .step-item {
            position: relative;
            min-height: 7.5rem;
            border-top: 1px solid rgb(255 255 255 / 35%);
            padding-top: 1.1rem;
        }

        .step-index {
            color: var(--gold);
            font-size: 0.8rem;
            font-weight: 800;
        }

        .step-name {
            margin-top: 0.45rem;
            font-size: 1.5rem;
            font-weight: 750;
        }

        .audience-item {
            border-bottom: 1px solid var(--line);
            padding: 1.2rem 0;
        }

        .advantage-section {
            background: #e8efea;
        }

        .trust-section {
            background: var(--white);
        }

        .trust-card {
            height: 100%;
            padding: 1.35rem;
            border: 1px solid var(--line);
            border-radius: 0.4rem;
            background: var(--paper);
        }

        .trust-card h3 {
            font-size: 1.05rem;
            font-weight: 800;
        }

        .trust-card p {
            margin-bottom: 0;
            color: var(--muted);
            line-height: 1.65;
        }

        .pricing-section {
            background: var(--paper);
        }

        .pricing-card {
            display: flex;
            height: 100%;
            flex-direction: column;
            padding: 1.6rem;
            border: 1px solid var(--line);
            border-top: 4px solid var(--green);
            border-radius: 0.4rem;
            background: var(--white);
            box-shadow: 0 0.35rem 1.1rem rgb(23 52 54 / 7%);
        }

        .pricing-card-premium {
            position: relative;
            border: 2px solid var(--coral);
            border-top-width: 5px;
            background: linear-gradient(180deg, #fff8f4 0%, var(--white) 42%);
            box-shadow: 0 0.8rem 2rem rgb(23 52 54 / 12%);
        }

        .pricing-card-business {
            border-top-color: var(--green-deep);
        }

        .pricing-badges {
            display: flex;
            min-height: 2rem;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.45rem;
        }

        .pricing-status {
            width: fit-content;
            border-radius: 999px;
            background: #e8efea;
            color: var(--green-deep);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            padding: 0.35rem 0.65rem;
            text-transform: uppercase;
        }

        .pricing-status-coming {
            background: #f9e8e1;
            color: #71301f;
        }

        .pricing-status-popular {
            background: var(--coral);
            color: #201c19;
        }

        .pricing-status-business {
            background: var(--green-deep);
            color: var(--white);
        }

        .pricing-positioning {
            max-width: 800px;
            color: var(--muted);
            font-size: 1.02rem;
            line-height: 1.7;
        }

        .pricing-positioning strong {
            color: var(--green-deep);
        }

        .pricing-card .pricing-description {
            min-height: 3.2rem;
        }

        .pricing-value {
            margin: 1rem 0;
            color: var(--green-deep);
            font-size: 1.65rem;
            font-weight: 850;
        }

        .pricing-copy,
        .pricing-card li {
            color: var(--muted);
            line-height: 1.6;
        }

        .pricing-card ul {
            padding-left: 1.1rem;
        }

        .pricing-card li + li {
            margin-top: 0.45rem;
        }

        .pricing-card .btn {
            margin-top: auto;
        }

        @media (min-width: 768px) {
            .pricing-card-premium {
                transform: translateY(-0.5rem);
            }
        }

        @media (max-width: 767.98px) {
            .pricing-card .pricing-description {
                min-height: 0;
            }
        }

        .advantage-quote {
            border-left: 4px solid var(--coral);
            padding-left: 1.25rem;
            color: var(--green-deep);
            font-size: 1.45rem;
            font-weight: 750;
            line-height: 1.45;
        }

        .cta-section {
            background: var(--coral);
            color: #201c19;
        }

        .cta-section .btn-brand {
            border-color: var(--green-deep);
            background: var(--green-deep);
            color: var(--white);
        }

        .cta-section .btn-brand:hover,
        .cta-section .btn-brand:focus-visible {
            background: #24544f;
        }

        .footer {
            background: var(--green-deep);
            color: rgb(255 255 255 / 78%);
        }

        .footer a {
            color: var(--white);
        }

        @media (max-width: 575.98px) {
            .hero {
                min-height: 33rem;
            }

            .hero h1 {
                font-size: 2.75rem;
            }

            .section-space {
                padding-top: 3.5rem;
                padding-bottom: 3.5rem;
            }

            .section-heading {
                font-size: 1.9rem;
            }
        }
    </style>
</head>

<body>
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-light py-3" aria-label="Main navigation">
            <div class="container-xl">
                <a class="brand" href="?page=home" aria-label="Zona AntiPhishing home">
                    <span class="brand-mark" aria-hidden="true">ZA</span>Zona AntiPhishing
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#homeNavigation"
                    aria-controls="homeNavigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="homeNavigation">
                    <div class="navbar-nav site-nav ms-auto align-items-lg-center gap-lg-2 py-3 py-lg-0">
                        <a class="nav-link" href="#features">Features</a>
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                        <a class="nav-link" href="#pricing">Pricing</a>
                        <a class="nav-link" href="?page=login">Login</a>
                        <a class="btn btn-brand ms-lg-2"
                            href="<?= htmlspecialchars($registerUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($registerLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero" aria-labelledby="hero-title">
            <img class="hero-image"
                src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&amp;fit=crop&amp;w=2400&amp;q=85"
                alt="" fetchpriority="high">
            <div class="hero-scrim" aria-hidden="true"></div>
            <div class="container-xl hero-content">
                <p class="eyebrow mb-3">Practical cybersecurity education</p>
                <h1 id="hero-title">
                    <span>Learn.</span>
                    <span>Practice.</span>
                    <span>Analyze.</span>
                    <span>Protect.</span>
                </h1>
                <p class="hero-copy mb-4">
                    Educational platform focused on phishing prevention through interactive learning, realistic
                    simulations, URL analysis and digital certification.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a class="btn btn-brand" href="<?= htmlspecialchars($startLearningUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        Start Learning
                    </a>
                    <a class="btn btn-quiet-light" href="#features">Explore Features</a>
                </div>
            </div>
        </section>

        <section class="problem-section section-space" aria-labelledby="problem-title">
            <div class="container-xl">
                <p class="section-kicker mb-2">The problem</p>
                <h2 class="section-heading mb-3" id="problem-title">A convincing message can put anyone at risk.</h2>
                <p class="problem-copy mb-0">
                    Phishing remains one of the most common cybersecurity threats and many users cannot identify
                    fraudulent messages before becoming victims.
                </p>
            </div>
        </section>

        <section class="section-space" id="features" aria-labelledby="solution-title">
            <div class="container-xl">
                <p class="section-kicker mb-2">Our solution</p>
                <h2 class="section-heading mb-4" id="solution-title">Build safer habits through active learning.</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
                    <div class="col">
                        <article class="feature-card feature-card-practice p-4">
                            <p class="feature-number mb-3">01 / LEARN</p>
                            <h3 class="h5 fw-bold">Interactive Courses</h3>
                            <p class="mb-0">Explore phishing concepts and learn the signals that make a message
                                suspicious.</p>
                        </article>
                    </div>
                    <div class="col">
                        <article class="feature-card feature-card-analyze p-4">
                            <p class="feature-number mb-3">02 / PRACTICE</p>
                            <h3 class="h5 fw-bold">Realistic Simulations</h3>
                            <p class="mb-0">Practice making decisions in scenarios inspired by suspicious messages.</p>
                        </article>
                    </div>
                    <div class="col">
                        <article class="feature-card feature-card-certify p-4">
                            <p class="feature-number mb-3">03 / ANALYZE</p>
                            <h3 class="h5 fw-bold">URL Analyzer</h3>
                            <p class="mb-0">Review common warning signs in a link before deciding what to do next.</p>
                        </article>
                    </div>
                    <div class="col">
                        <article class="feature-card p-4">
                            <p class="feature-number mb-3">04 / CERTIFY</p>
                            <h3 class="h5 fw-bold">Digital Certificates</h3>
                            <p class="mb-0">Recognize course completion with a certificate you can view and download.
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="steps-section section-space" id="how-it-works" aria-labelledby="steps-title">
            <div class="container-xl">
                <p class="section-kicker mb-2">How it works</p>
                <h2 class="section-heading mb-5" id="steps-title">One connected path from knowledge to action.</h2>
                <div class="row row-cols-2 row-cols-lg-4 g-4">
                    <div class="col">
                        <div class="step-item"><span class="step-index">01</span>
                            <p class="step-name mb-0">Learn</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="step-item"><span class="step-index">02</span>
                            <p class="step-name mb-0">Practice</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="step-item"><span class="step-index">03</span>
                            <p class="step-name mb-0">Analyze</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="step-item"><span class="step-index">04</span>
                            <p class="step-name mb-0">Certify</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-space" aria-labelledby="audience-title">
            <div class="container-xl">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <p class="section-kicker mb-2">Who it is for</p>
                        <h2 class="section-heading mb-3" id="audience-title">Safer digital decisions start with
                            practice.</h2>
                        <p class="audience-copy mb-0">Build practical awareness for everyday messages, accounts and
                            online work.</p>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="audience-item">
                            <h3 class="h5 fw-bold mb-1">Students</h3>
                            <p class="audience-copy mb-0">Develop useful online safety habits while learning.</p>
                        </div>
                        <div class="audience-item">
                            <h3 class="h5 fw-bold mb-1">Professionals</h3>
                            <p class="audience-copy mb-0">Practice recognizing suspicious workplace communications.</p>
                        </div>
                        <div class="audience-item">
                            <h3 class="h5 fw-bold mb-1">Small Businesses</h3>
                            <p class="audience-copy mb-0">Help teams recognize common phishing warning signs.</p>
                        </div>
                        <div class="audience-item">
                            <h3 class="h5 fw-bold mb-1">Educational Institutions</h3>
                            <p class="audience-copy mb-0">Bring guided cybersecurity practice into learning
                                environments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="advantage-section section-space" aria-labelledby="advantage-title">
            <div class="container-xl">
                <div class="row align-items-center g-4">
                    <div class="col-lg-5">
                        <p class="section-kicker mb-2">Why Zona AntiPhishing</p>
                        <h2 class="section-heading mb-0" id="advantage-title">Knowledge matters most when you can put it
                            into practice.</h2>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <p class="advantage-quote mb-3">Most resources provide only theory.</p>
                        <p class="audience-copy fs-5 mb-0">
                            Zona AntiPhishing allows users to learn, practice, analyze and certify knowledge in one
                            platform.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="trust-section section-space" id="trust" aria-labelledby="trust-title">
            <div class="container-xl">
                <p class="section-kicker mb-2">Trust and transparency</p>
                <h2 class="section-heading mb-4" id="trust-title">A learning platform built around safer habits.</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3">
                    <div class="col">
                        <article class="trust-card">
                            <h3>Educational by design</h3>
                            <p>Learn to recognize phishing; the platform is not an antivirus or a guarantee that a link is safe.</p>
                        </article>
                    </div>
                    <div class="col">
                        <article class="trust-card">
                            <h3>Learn, practice, achieve</h3>
                            <p>Build understanding with courses and practice, then track learning achievements and certificates.</p>
                        </article>
                    </div>
                    <div class="col">
                        <article class="trust-card">
                            <h3>Privacy matters</h3>
                            <p>Account details support sign-in; learning activity supports your progress and earned certificates.</p>
                        </article>
                    </div>
                    <div class="col">
                        <article class="trust-card">
                            <h3>Security-oriented</h3>
                            <p>Practice identifying warning signs and make informed decisions before trusting messages or links.</p>
                        </article>
                    </div>
                </div>
                <p class="small text-secondary mt-3 mb-0">
                    Read our <a href="?page=privacy-policy">Privacy Policy</a> and
                    <a href="?page=terms">Terms of Service</a>.
                </p>
            </div>
        </section>

        <section class="pricing-section section-space" id="pricing" aria-labelledby="pricing-title">
            <div class="container-xl">
                <p class="section-kicker mb-2">Flexible learning paths</p>
                <h2 class="section-heading mb-3" id="pricing-title">Start free. Grow your security awareness over time.</h2>
                <p class="pricing-positioning mb-4">
                    Our long-term model is designed to keep core learning accessible through Free, with optional
                    <strong>individual Premium learning</strong> and <strong>Business training for organizations</strong>
                    as the platform grows. Premium and Business are planned offerings only: there is no payment,
                    subscription, or paid access today.
                </p>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <div class="col">
                        <article class="pricing-card">
                            <div class="pricing-badges">
                                <span class="pricing-status">Available now</span>
                            </div>
                            <h3 class="h4 fw-bold mt-3 mb-0">Free</h3>
                            <p class="pricing-value">Free today</p>
                            <p class="pricing-copy pricing-description">A complete starting point for building everyday phishing awareness.</p>
                            <ul class="mb-4">
                                <li>Available foundational courses and quizzes</li>
                                <li>Interactive simulations and URL analysis</li>
                                <li>Learning progress tracking</li>
                                <li>Educational course-completion certificates</li>
                            </ul>
                            <a class="btn btn-brand" href="<?= htmlspecialchars($registerUrl, ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($registerLabel, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </article>
                    </div>
                    <div class="col">
                        <article class="pricing-card pricing-card-premium" aria-label="Premium, most popular future plan">
                            <div class="pricing-badges">
                                <span class="pricing-status pricing-status-coming">Coming Soon</span>
                                <span class="pricing-status pricing-status-popular">Most Popular Future Plan</span>
                            </div>
                            <h3 class="h4 fw-bold mt-3 mb-0">Premium</h3>
                            <p class="pricing-value">For individual learners</p>
                            <p class="pricing-copy pricing-description">A deeper, more structured learning journey for people who want to build advanced phishing-defense skills.</p>
                            <ul class="mb-4">
                                <li>Everything in Free</li>
                                <li>Advanced phishing courses</li>
                                <li>Advanced simulations</li>
                                <li>Expanded learning paths</li>
                                <li>Premium certificates</li>
                                <li>Detailed progress reports</li>
                            </ul>
                            <span class="small text-secondary mt-auto">Future concept. Not available for purchase yet.</span>
                        </article>
                    </div>
                    <div class="col">
                        <article class="pricing-card pricing-card-business">
                            <div class="pricing-badges">
                                <span class="pricing-status pricing-status-coming">Coming Soon</span>
                                <span class="pricing-status pricing-status-business">For Organizations</span>
                            </div>
                            <h3 class="h4 fw-bold mt-3 mb-0">Business</h3>
                            <p class="pricing-value">For teams</p>
                            <p class="pricing-copy pricing-description">A future organization-focused offer to help teams build consistent security awareness together.</p>
                            <ul class="mb-4">
                                <li>Everything in Premium</li>
                                <li>Team training</li>
                                <li>Group learning</li>
                                <li>Organization dashboard</li>
                                <li>Corporate certificates</li>
                                <li>Awareness training programs</li>
                            </ul>
                            <span class="small text-secondary mt-auto">Future concept. Not available for purchase yet.</span>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-section section-space" aria-labelledby="cta-title">
            <div
                class="container-xl d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                <div>
                    <p class="small fw-bold text-uppercase mb-2">Take the next step</p>
                    <h2 class="section-heading mb-0" id="cta-title">Start Your Cybersecurity Journey Today</h2>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a class="btn btn-brand" href="<?= htmlspecialchars($registerUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars($registerLabel, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                    <a class="btn btn-outline-dark fw-bold px-4 py-3" href="?page=login">Login</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer py-4">
        <div class="container-xl">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                    <p class="fw-bold mb-1">Zona AntiPhishing</p>
                    <p class="small mb-0">Learn to recognize phishing. Practice safer decisions.</p>
                </div>
                <nav class="d-flex flex-wrap gap-3" aria-label="Footer navigation">
                    <a href="#pricing">Pricing</a>
                    <a href="?page=privacy-policy">Privacy Policy</a>
                    <a href="?page=terms">Terms of Service</a>
                    <a href="#contact">Contact</a>
                </nav>
            </div>
            <div id="contact" class="small mt-3 pt-3 border-top border-light border-opacity-25">
                Contact details are not published yet. Please check back for updates.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>