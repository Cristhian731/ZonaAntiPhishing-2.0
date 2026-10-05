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
            --zap-primary: #1E6559;
            --zap-deep: #123A37;
            --zap-cta: #E66E50;
            --zap-cta-hover: #B7432A;
            --zap-cta-ink: #102826;
            --zap-canvas: #F4F6F1;
            --zap-achievement: #EDBD59;
            --zap-ink: #173436;
            --zap-muted: #546768;
            --zap-border: #D8E0D9;
            --zap-surface: #FBFAF5;
            --zap-surface-tint: #EDF3EC;
            --zap-success: #2F765B;
            --zap-warning: #A96B14;
            --zap-danger: #B84943;
            --zap-radius: 0.5rem;
            --zap-shadow: 0 0.35rem 1.1rem rgb(18 58 55 / 8%);
            --bs-box-shadow-sm: var(--zap-shadow);
            --bs-primary: var(--zap-primary);
            --bs-primary-rgb: 30, 101, 89;
            --bs-success: var(--zap-success);
            --bs-warning: var(--zap-warning);
            --bs-danger: var(--zap-danger);
            --bs-link-color: var(--zap-primary);
            --bs-link-hover-color: var(--zap-deep);
            --bs-body-bg: var(--zap-canvas);
            --bs-body-color: var(--zap-ink);
            --bs-border-color: var(--zap-border);
            --bs-success-rgb: 47, 118, 91;
            --bs-warning-rgb: 169, 107, 20;
            --bs-danger-rgb: 184, 73, 67;
            --bs-list-group-bg: var(--zap-surface);
            --bs-list-group-color: var(--zap-ink);
            --bs-list-group-border-color: var(--zap-border);
        }

        body {
            min-height: 100vh;
            background: var(--zap-canvas);
            color: var(--zap-ink);
            font-family: "Trebuchet MS", "Segoe UI", system-ui, sans-serif;
        }

        .site-navbar {
            background: var(--zap-deep);
            box-shadow: 0 2px 12px rgb(18 58 55 / 20%);
            border-bottom: 2px solid rgb(255 255 255 / 16%);
        }

        .brand-mark {
            display: inline-grid;
            width: 2rem;
            height: 2rem;
            place-items: center;
            border-radius: var(--zap-radius);
            background: var(--zap-cta);
            color: var(--zap-cta-ink);
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
            position: relative;
            display: inline-flex;
            min-height: 2.75rem;
            align-items: center;
            border-radius: var(--zap-radius);
            color: rgb(255 255 255 / 82%);
            padding: 0.55rem 0.8rem;
            font-weight: 600;
            transition: background-color 150ms ease, color 150ms ease, box-shadow 150ms ease;
        }

        .site-navbar .navbar-nav .nav-link:hover,
        .site-navbar .navbar-nav .nav-link:focus-visible,
        .site-navbar .navbar-nav .nav-link.active {
            background: rgb(255 255 255 / 10%);
            color: #fff;
            box-shadow: inset 0 -2px 0 var(--zap-cta);
        }

        .site-navbar .navbar-nav .nav-link:focus-visible,
        .site-navbar .navbar-brand:focus-visible,
        .site-navbar .navbar-toggler:focus-visible {
            outline: 3px solid var(--zap-cta);
            outline-offset: 2px;
        }

        .site-navbar .navbar-nav .nav-link-logout {
            color: #fff;
        }

        .btn {
            min-height: 2.75rem;
            border-radius: var(--zap-radius);
            font-weight: 650;
            transition: background-color 150ms ease, border-color 150ms ease, box-shadow 150ms ease, color 150ms ease;
        }

        .btn:focus-visible {
            outline: 3px solid var(--zap-cta);
            outline-offset: 2px;
            box-shadow: none;
        }

        .btn-primary {
            --bs-btn-color: var(--zap-cta-ink);
            --bs-btn-bg: var(--zap-cta);
            --bs-btn-border-color: var(--zap-cta);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--zap-cta-hover);
            --bs-btn-hover-border-color: var(--zap-cta-hover);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--zap-cta-hover);
            --bs-btn-active-border-color: var(--zap-cta-hover);
            --bs-btn-disabled-color: var(--zap-cta-ink);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--zap-deep);
            --bs-btn-border-color: var(--zap-primary);
            --bs-btn-hover-bg: var(--zap-primary);
            --bs-btn-hover-border-color: var(--zap-deep);
            --bs-btn-hover-color: #fff;
        }

        .btn-secondary,
        .btn-outline-secondary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--zap-deep);
            --bs-btn-border-color: var(--zap-deep);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--zap-primary);
            --bs-btn-hover-border-color: var(--zap-primary);
            --bs-btn-active-bg: var(--zap-deep);
            --bs-btn-active-border-color: var(--zap-deep);
        }

        .btn-outline-secondary {
            --bs-btn-color: var(--zap-deep);
            --bs-btn-bg: transparent;
            --bs-btn-hover-color: #fff;
        }

        .btn-achievement {
            --bs-btn-color: var(--zap-deep);
            --bs-btn-bg: var(--zap-achievement);
            --bs-btn-border-color: var(--zap-achievement);
            --bs-btn-hover-color: var(--zap-deep);
            --bs-btn-hover-bg: #E0AE45;
            --bs-btn-hover-border-color: #E0AE45;
        }

        .card {
            --bs-card-border-radius: var(--zap-radius);
            --bs-card-border-color: var(--zap-border);
            --bs-card-bg: var(--zap-surface);
        }

        .bg-white {
            background-color: var(--zap-surface) !important;
        }

        .bg-light {
            background-color: var(--zap-surface-tint) !important;
        }

        .text-primary {
            color: var(--zap-primary) !important;
        }

        .text-bg-primary {
            color: #fff !important;
            background-color: var(--zap-primary) !important;
        }

        .text-bg-success {
            color: #fff !important;
            background-color: var(--zap-success) !important;
        }

        .text-bg-warning {
            color: #fff !important;
            background-color: var(--zap-warning) !important;
        }

        .text-bg-danger {
            color: #fff !important;
            background-color: var(--zap-danger) !important;
        }

        .text-bg-secondary {
            color: #fff !important;
            background-color: var(--zap-deep) !important;
        }

        .badge {
            font-weight: 700;
        }

        .table {
            --bs-table-bg: var(--zap-surface);
            --bs-table-border-color: var(--zap-border);
            --bs-table-color: var(--zap-ink);
        }

        .table thead th {
            color: var(--zap-muted);
            font-size: 0.75rem;
            font-weight: 750;
            letter-spacing: 0.035em;
            text-transform: uppercase;
        }

        .dashboard-stat {
            height: 100%;
            border: 1px solid var(--zap-border);
            border-top: 4px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .dashboard-stat.stat-deep {
            border-top-color: var(--zap-deep);
        }

        .dashboard-stat.stat-black {
            border-top-color: var(--zap-ink);
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

        .progress {
            height: 0.75rem;
            border-radius: 999px;
            background: #E2EAE1;
        }

        .progress-bar {
            background: var(--zap-primary);
        }

        .form-control,
        .form-select {
            min-height: 2.75rem;
            border-color: var(--zap-border);
            border-radius: var(--zap-radius);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--zap-primary);
            box-shadow: 0 0 0 0.2rem rgb(30 101 89 / 17%);
        }

        .text-body-secondary {
            color: var(--zap-muted) !important;
        }

        .alert-light {
            color: var(--zap-ink);
            border-color: var(--zap-border);
            background-color: var(--zap-surface-tint);
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            color: var(--zap-ink);
        }

        .quick-action-card {
            display: flex;
            min-height: 15rem;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 1.65rem;
            border: 1px solid var(--zap-border);
            border-top: 5px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: linear-gradient(145deg, var(--zap-surface) 0%, #edf5fc 100%);
            box-shadow: 0 0.4rem 1.15rem rgb(18 58 55 / 8%);
            color: var(--zap-ink);
            text-decoration: none;
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }

        .quick-action-card:hover,
        .quick-action-card:focus-visible {
            border-color: var(--zap-primary);
            box-shadow: 0 0.8rem 1.6rem rgb(18 58 55 / 13%);
            color: var(--zap-ink);
            transform: translateY(-4px);
        }

        .quick-action-card-analyzer {
            border-top-color: var(--zap-deep);
            background: linear-gradient(145deg, var(--zap-surface) 0%, #eaf1f9 100%);
        }

        .quick-action-label {
            color: var(--zap-deep);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.075em;
            text-transform: uppercase;
        }

        .quick-action-card:focus-visible,
        .course-card:focus-within a:focus-visible {
            outline: 3px solid var(--zap-cta);
            outline-offset: 3px;
        }

        .quick-action-icon {
            position: relative;
            display: grid;
            width: 4.25rem;
            height: 4.25rem;
            place-items: center;
            border: 1px solid rgb(30 101 89 / 24%);
            border-radius: 1rem;
            background: linear-gradient(145deg, rgb(30 101 89 / 13%), rgb(18 58 55 / 6%));
            color: var(--zap-primary);
            box-shadow: inset 0 1px 0 rgb(255 255 255 / 75%), 0 0.3rem 0.7rem rgb(18 58 55 / 7%);
        }

        .quick-action-icon-shield::before {
            width: 1.8rem;
            height: 2.05rem;
            background: currentColor;
            clip-path: polygon(50% 0, 94% 18%, 88% 61%, 72% 82%, 50% 100%, 28% 82%, 12% 61%, 6% 18%);
            content: "";
        }

        .quick-action-icon-shield::after {
            position: absolute;
            top: 1.67rem;
            left: 1.57rem;
            width: 1rem;
            height: 0.55rem;
            border-bottom: 2px solid #fff;
            border-left: 2px solid #fff;
            content: "";
            transform: rotate(-45deg);
        }

        .quick-action-icon-search::before {
            width: 1.55rem;
            height: 1.55rem;
            border: 3px solid currentColor;
            border-radius: 50%;
            content: "";
            transform: translate(-0.15rem, -0.15rem);
        }

        .quick-action-icon-search::after {
            position: absolute;
            top: 2.35rem;
            left: 2.35rem;
            width: 0.85rem;
            border-top: 3px solid currentColor;
            content: "";
            transform: rotate(45deg);
            transform-origin: left center;
        }

        .quick-action-card h3 {
            letter-spacing: -0.02em;
        }

        .quick-action-card p {
            max-width: 34rem;
            line-height: 1.7;
        }

        .quick-action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--zap-deep);
            font-weight: 750;
        }

        .quick-action-link span {
            transition: transform 160ms ease;
        }

        .quick-action-card:hover .quick-action-link span,
        .quick-action-card:focus-visible .quick-action-link span {
            transform: translateX(3px);
        }

        .course-card {
            overflow: hidden;
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        .course-card:hover {
            box-shadow: 0 0.7rem 1.5rem rgb(18 58 55 / 12%);
            transform: translateY(-2px);
        }

        .course-card-kicker {
            color: var(--zap-primary);
            font-size: 0.75rem;
            font-weight: 750;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .course-card {
            border-top-width: 5px;
            background: linear-gradient(155deg, var(--zap-surface) 0%, #f3f8fd 100%);
        }

        .course-card-title {
            letter-spacing: -0.025em;
            line-height: 1.25;
        }

        .course-card-title,
        .course-lesson-copy h3,
        .learning-lesson-title {
            overflow-wrap: anywhere;
        }

        .course-card-description,
        .course-detail-description,
        .lesson-objective p,
        .lesson-content {
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .course-card-meta {
            border-top: 1px solid var(--zap-border);
            padding-top: 1rem;
        }

        .course-card-meta strong {
            color: var(--zap-deep);
            font-size: 1.05rem;
        }

        .course-card-meta-mark {
            display: inline-grid;
            width: 2.75rem;
            height: 2.75rem;
            flex: 0 0 auto;
            place-items: center;
            border: 1px solid rgb(30 101 89 / 18%);
            border-radius: 0.75rem;
            background: rgb(30 101 89 / 8%);
        }

        .course-card-meta-mark::before {
            width: 1.1rem;
            height: 1.1rem;
            border: 2px solid var(--zap-primary);
            border-radius: 0.2rem;
            box-shadow: 0.25rem 0.25rem 0 -0.08rem var(--zap-surface), 0.25rem 0.25rem 0 0.04rem var(--zap-primary);
            content: "";
        }

        .course-card-cta {
            display: inline-flex;
            width: 100%;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1rem;
        }

        .course-level-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgb(30 101 89 / 28%);
            border-radius: 999px;
            background: rgb(30 101 89 / 11%);
            color: var(--zap-deep);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.035em;
            padding: 0.45rem 0.75rem;
            text-transform: uppercase;
        }

        .course-level-intermediate {
            border-color: var(--zap-deep);
            background: var(--zap-deep);
            color: #fff;
        }

        .course-level-advanced {
            border-color: var(--zap-deep);
            background: var(--zap-deep);
            color: #fff;
        }

        .course-hero,
        .simulation-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--zap-border);
            border-left: 5px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: linear-gradient(115deg, #fff 0%, #edf5fc 100%);
            box-shadow: var(--zap-shadow);
        }

        .simulation-hero {
            border-top: 5px solid var(--zap-primary);
            background: var(--zap-surface);
        }

        .simulation-card {
            border-top-color: var(--zap-primary);
            background: linear-gradient(155deg, var(--zap-surface) 0%, #f4f7fb 100%);
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        .simulation-card:hover {
            box-shadow: 0 0.7rem 1.5rem rgb(18 58 55 / 12%);
            transform: translateY(-2px);
        }

        .simulation-card-mark {
            min-height: 3rem;
        }

        .simulation-card-icon {
            position: relative;
            display: inline-grid;
            width: 3.25rem;
            height: 3.25rem;
            place-items: center;
            border: 1px solid var(--zap-border);
            border-radius: 0.9rem;
            background: var(--zap-surface-tint);
        }

        .simulation-step-panel {
            border-left-color: var(--zap-primary);
        }

        .simulation-card-icon::before {
            width: 1.15rem;
            height: 1.15rem;
            border: 2px solid var(--zap-deep);
            border-radius: 50%;
            content: "";
        }

        .simulation-card-icon::after {
            position: absolute;
            width: 0.4rem;
            height: 0.4rem;
            border-top: 2px solid var(--zap-deep);
            border-right: 2px solid var(--zap-deep);
            content: "";
            transform: translate(0.15rem, -0.15rem) rotate(45deg);
        }

        .simulation-card-description {
            line-height: 1.7;
        }

        .simulation-card-cta {
            display: inline-flex;
            width: 100%;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .simulation-step-panel.simulation-briefing {
            border-left-width: 6px;
            background: linear-gradient(112deg, var(--zap-surface), var(--zap-surface-tint));
        }

        .simulation-step-panel .progress {
            height: 0.65rem;
        }

        .simulation-step-panel .progress-bar {
            background: var(--zap-primary);
        }

        .simulation-scenario-card {
            border-top-color: var(--zap-deep);
            background: linear-gradient(155deg, var(--zap-surface), #f0f6fc);
        }

        .simulation-briefing-label {
            letter-spacing: 0.075em;
        }

        .simulation-scenario-text {
            padding: 1.25rem 1.35rem;
            border-left: 3px solid var(--zap-primary);
            border-radius: 0 var(--zap-radius) var(--zap-radius) 0;
            background: rgb(255 255 255 / 82%);
            font-size: 1.15rem;
            line-height: 1.85;
            overflow-wrap: anywhere;
        }

        .simulation-decision {
            padding-top: 1.25rem;
            border-top: 1px solid var(--zap-border);
        }

        .simulation-choice {
            min-height: 3.75rem;
            border-color: var(--zap-border) !important;
            background: var(--zap-surface);
            transition: border-color 150ms ease, background-color 150ms ease, box-shadow 150ms ease;
        }

        .simulation-choice:hover,
        .simulation-choice:focus-within {
            border-color: var(--zap-primary) !important;
            background: rgb(30 101 89 / 5%);
            box-shadow: 0 0.25rem 0.7rem rgb(18 58 55 / 8%);
        }

        .simulation-outcome {
            border-top-color: var(--zap-primary);
            background: var(--zap-surface);
        }

        .simulation-library-hero {
            border: 1px solid var(--zap-border);
            border-top: 5px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: linear-gradient(112deg, var(--zap-surface) 0%, var(--zap-surface-tint) 100%);
            box-shadow: 0 0.7rem 1.5rem rgb(18 58 55 / 9%);
        }

        .simulation-library-copy {
            max-width: 48rem;
        }

        .simulation-library-copy p:last-child {
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .simulation-library-count {
            display: grid;
            min-width: 10rem;
            justify-items: center;
            gap: 0.25rem;
            padding: 1rem 1.5rem;
            border: 1px solid var(--zap-border);
            border-radius: var(--zap-radius);
            background: rgb(255 255 255 / 78%);
            color: var(--zap-muted);
        }

        .simulation-library-count .simulation-card-icon {
            margin-bottom: 0.4rem;
        }

        .simulation-library-count strong {
            color: var(--zap-deep);
            font-size: 2rem;
            line-height: 1.1;
        }

        .simulation-progress-percent {
            display: inline-grid;
            min-width: 3.5rem;
            min-height: 3.5rem;
            place-items: center;
            border: 2px solid var(--zap-primary);
            border-radius: 50%;
            background: var(--zap-surface);
            color: var(--zap-deep);
            font-size: 1rem;
            font-weight: 800;
        }

        .simulation-step-panel .progress {
            height: 0.85rem;
            overflow: hidden;
            border: 1px solid rgb(18 58 55 / 12%);
            background-color: #e4ebe4;
            background-image: repeating-linear-gradient(
                90deg,
                transparent 0 calc(20% - 1px),
                rgb(18 58 55 / 12%) calc(20% - 1px) 20%
            );
            background-size: 100% 100%;
        }

        .simulation-step-panel .progress-bar {
            border-radius: 999px;
            background: linear-gradient(90deg, var(--zap-primary), var(--zap-deep));
            box-shadow: 0 0 0.5rem rgb(30 101 89 / 18%);
            transition: width 250ms ease;
        }

        .simulation-step-markers {
            display: flex;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .simulation-step-markers span {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background: #cbd7e2;
        }

        .simulation-step-markers span.is-reached {
            background: var(--zap-primary);
        }

        .simulation-completion-hero {
            padding: 1.5rem;
            border: 1px solid rgb(198 162 82 / 42%);
            border-left: 5px solid var(--zap-achievement);
            border-radius: var(--zap-radius);
            background: linear-gradient(115deg, var(--zap-surface), #fbf8f0);
        }

        .simulation-score-card {
            display: grid;
            min-width: 13rem;
            justify-items: center;
            gap: 0.2rem;
            padding: 1rem 1.5rem;
            border: 1px solid rgb(198 162 82 / 48%);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            color: var(--zap-muted);
            text-align: center;
        }

        .simulation-score-card > span:first-child {
            color: var(--zap-deep);
            letter-spacing: 0.06em;
        }

        .simulation-score-card strong {
            color: var(--zap-deep);
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .simulation-score-card strong span {
            font-size: 1.25rem;
        }

        .simulation-reflection-heading {
            padding-bottom: 0.75rem;
        }

        .simulation-reflection-item {
            border-color: var(--zap-border);
            background: rgb(255 255 255 / 80%);
        }

        .simulation-reflection-item + .simulation-reflection-item {
            border-top-width: 0;
        }

        .course-detail-hero {
            border-left-width: 6px;
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .course-detail-copy {
            min-width: 0;
            max-width: 52rem;
        }

        .course-detail-description {
            font-size: 1.05rem;
            line-height: 1.8;
        }

        .course-detail-level {
            font-size: 0.8rem;
            padding: 0.6rem 0.9rem;
        }

        .course-detail-count {
            color: var(--zap-muted);
        }

        .course-detail-count strong {
            color: var(--zap-deep);
            font-size: 1.5rem;
            margin-right: 0.25rem;
        }

        .course-detail-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
        }

        .course-lesson-card {
            border-top-width: 3px;
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        .course-lesson-card:hover {
            box-shadow: 0 0.65rem 1.4rem rgb(18 58 55 / 10%);
            transform: translateY(-2px);
        }

        .course-lesson-number {
            display: inline-grid;
            width: 2.5rem;
            height: 2.5rem;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 50%;
            background: rgb(30 101 89 / 9%);
            color: var(--zap-deep);
            font-weight: 750;
        }

        .course-lesson-copy {
            min-width: 0;
        }

        .simulation-step-panel {
            border: 1px solid var(--zap-border);
            border-left: 4px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
        }

        .learning-sequence-header {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--zap-border);
            border-top: 5px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .learning-course-name {
            max-width: 100%;
            overflow-wrap: anywhere;
        }

        .learning-reading-time {
            font-weight: 650;
        }

        .learning-sequence-progress {
            max-width: 40rem;
        }

        .lesson-objective {
            border: 1px solid var(--zap-border);
            border-left: 4px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .lesson-objective p:last-child {
            line-height: 1.75;
        }

        .lesson-content {
            max-width: 72ch;
            margin-inline: auto;
            font-size: 1.075rem;
            line-height: 1.9;
            letter-spacing: 0.003em;
        }

        .lesson-content p {
            margin-bottom: 1.5rem;
        }

        .lesson-content li {
            padding-left: 0.15rem;
            line-height: 1.85;
        }

        .lesson-content h3 {
            padding-bottom: 0.65rem;
            border-bottom: 1px solid var(--zap-border);
        }

        .lesson-content section {
            margin-bottom: 2.5rem !important;
        }

        .lesson-content-card > .card-body {
            padding: clamp(1.25rem, 4vw, 3.5rem) !important;
        }

        .lesson-content-card #lesson-content-title {
            max-width: 72ch;
            margin-inline: auto;
            margin-bottom: 2rem !important;
        }

        .quiz-transition {
            border: 1px solid var(--zap-border);
            border-left: 6px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .quiz-transition h2 {
            overflow-wrap: anywhere;
        }

        .quiz-transition-cta {
            min-width: 11rem;
            box-shadow: 0 0.3rem 0.8rem rgb(18 58 55 / 12%);
        }

        .lesson-navigation > div {
            min-width: 0;
        }

        @media (max-width: 575.98px) {
            .course-detail-cta,
            .quiz-transition-cta {
                width: 100%;
            }

            .lesson-navigation a {
                width: 100%;
            }
        }

        .risk-result {
            border: 1px solid var(--zap-border);
            border-left: 5px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .risk-result-low {
            border-left-color: var(--zap-success);
        }

        .risk-result-medium {
            border-left-color: var(--zap-warning);
        }

        .risk-result-high {
            border-left-color: var(--zap-danger);
        }

        .url-analyzer-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--zap-border);
            border-top: 5px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .url-analyzer-hero-copy {
            max-width: 50rem;
        }

        .url-analyzer-hero-copy p:last-child {
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .url-analyzer-hero-mark {
            display: grid;
            width: 5.75rem;
            height: 5.75rem;
            flex: 0 0 auto;
            place-items: center;
            border: 1px solid rgb(30 101 89 / 18%);
            border-radius: 1.25rem;
            background: rgb(255 255 255 / 75%);
        }

        .url-analyzer-hero-mark .quick-action-icon {
            width: 4rem;
            height: 4rem;
        }

        .url-analyzer-hero-mark .quick-action-icon-search::after {
            top: 2.2rem;
            left: 2.2rem;
        }

        .url-analyzer-guidance {
            display: grid;
            grid-template-columns: minmax(12rem, 0.8fr) minmax(0, 2fr);
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem 1.5rem;
            border: 1px solid var(--zap-border);
            border-left: 4px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .url-analyzer-guidance-points {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .url-analyzer-guidance-points p {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin: 0;
            color: var(--zap-muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .url-guidance-number {
            display: inline-grid;
            width: 1.65rem;
            height: 1.65rem;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 50%;
            background: rgb(30 101 89 / 10%);
            color: var(--zap-deep);
            font-size: 0.68rem;
            font-weight: 800;
        }

        .url-analyzer-form-card {
            padding: 1.4rem 1.5rem;
            border: 1px solid var(--zap-border);
            border-top: 3px solid var(--zap-primary);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .url-analyzer-submit {
            padding-inline: 1.5rem;
        }

        .risk-result {
            border-top-width: 5px;
        }

        .risk-level {
            display: inline-flex;
            align-items: center;
            border: 1px solid currentColor;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 800;
            padding: 0.7rem 1.15rem;
            box-shadow: 0 0.35rem 0.9rem rgb(19 49 76 / 12%);
        }

        .risk-result-header {
            padding: 1rem 1.1rem 1.25rem;
            border: 1px solid var(--zap-border);
            border-radius: var(--zap-radius);
            background: rgb(255 255 255 / 78%);
        }

        .risk-result-level {
            min-width: 10rem;
            padding: 0.8rem 1rem;
            border: 1px solid var(--zap-border);
            border-radius: var(--zap-radius);
            background: rgb(255 255 255 / 80%);
        }

        .risk-result-high .risk-result-level {
            border-color: rgb(184 73 67 / 35%);
            background: rgb(184 73 67 / 6%);
        }

        .risk-result-medium .risk-result-level {
            border-color: rgb(169 107 20 / 35%);
            background: rgb(169 107 20 / 7%);
        }

        .risk-result-low .risk-result-level {
            border-color: rgb(47 118 91 / 35%);
            background: rgb(47 118 91 / 6%);
        }

        .risk-result-level .risk-level {
            justify-content: center;
            min-width: 8.5rem;
        }

        .risk-findings-panel,
        .risk-recommendation {
            padding: 1.25rem;
            border: 1px solid var(--zap-border);
            border-radius: var(--zap-radius);
            background: var(--zap-surface);
        }

        .risk-findings-list {
            display: grid;
            gap: 0.65rem;
            padding: 0;
            list-style: none;
        }

        .risk-findings-list li {
            position: relative;
            padding: 0.75rem 0.85rem 0.75rem 2rem;
            border: 1px solid rgb(30 101 89 / 12%);
            border-radius: var(--zap-radius);
            background: var(--zap-surface-tint);
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .risk-findings-list li::before {
            position: absolute;
            top: 1.08rem;
            left: 0.9rem;
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background: var(--zap-primary);
            content: "";
        }

        .risk-recommendation {
            border-left: 4px solid var(--zap-primary);
            background: linear-gradient(145deg, var(--zap-surface), #fbf8f0);
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .risk-result > .row h3 {
            letter-spacing: -0.015em;
        }

        @media (max-width: 575.98px) {
            .quick-action-card {
                min-height: 13.5rem;
                padding: 1.25rem;
            }

            .risk-result-level {
                width: 100%;
            }

            .risk-result-level .risk-level {
                width: 100%;
            }

            .simulation-scenario-text {
                padding: 1rem;
                font-size: 1.05rem;
            }

            .url-analyzer-hero-mark {
                display: none;
            }

            .url-analyzer-guidance {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.9rem;
                padding: 1.1rem;
            }

            .url-analyzer-guidance-points {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.65rem;
            }

            .url-analyzer-form-card {
                padding: 1.15rem;
            }

            .url-analyzer-input-group {
                flex-direction: column;
                gap: 0.65rem;
            }

            .url-analyzer-input-group > .form-control,
            .url-analyzer-input-group > .btn {
                width: 100%;
                border-radius: var(--zap-radius) !important;
            }

            .url-analyzer-input-group > .btn {
                min-height: 3rem;
            }

            .simulation-library-count {
                width: 100%;
                grid-template-columns: auto 1fr auto;
                justify-items: start;
                align-items: center;
                gap: 0.35rem 0.75rem;
                padding: 0.75rem 1rem;
            }

            .simulation-library-count .simulation-card-icon {
                display: none;
            }

            .simulation-library-count strong {
                grid-column: 3;
                grid-row: 1 / span 2;
            }

            .simulation-library-count > span:last-child {
                grid-column: 2;
            }

            .simulation-completion-hero {
                padding: 1.1rem;
            }

            .simulation-score-card {
                width: 100%;
            }
        }

        .achievement-card {
            border-top-color: var(--zap-achievement);
            background: linear-gradient(145deg, var(--zap-surface) 0%, #fbf5e8 100%);
        }

        .dashboard-stat,
        .course-card,
        .simulation-card {
            background-color: var(--zap-surface);
            box-shadow: 0 0.35rem 1.1rem rgb(18 58 55 / 8%);
        }

        .course-card,
        .simulation-card {
            background-image: linear-gradient(155deg, var(--zap-surface) 0%, var(--zap-surface-tint) 100%);
        }

        .course-card-meta-mark,
        .quick-action-icon,
        .url-guidance-number {
            border-color: rgb(30 101 89 / 20%);
            background: rgb(30 101 89 / 9%);
        }

        .course-level-badge:not(.course-level-intermediate):not(.course-level-advanced) {
            border-color: rgb(30 101 89 / 28%);
            background: rgb(30 101 89 / 11%);
        }

        .quick-action-card {
            border-top-color: var(--zap-primary);
            background: linear-gradient(145deg, var(--zap-surface) 0%, var(--zap-surface-tint) 100%);
            box-shadow: 0 0.55rem 1.5rem rgb(18 58 55 / 9%);
        }

        .quick-action-card-analyzer {
            border-top-color: var(--zap-primary);
            background: var(--zap-surface);
        }

        .quick-action-card:hover,
        .quick-action-card:focus-visible,
        .course-card:hover,
        .simulation-card:hover,
        .course-lesson-card:hover {
            box-shadow: 0 0.85rem 1.8rem rgb(18 58 55 / 15%);
        }

        .course-card-kicker,
        .course-card-meta strong,
        .course-detail-count strong,
        .simulation-library-count strong,
        .simulation-score-card strong {
            color: var(--zap-deep);
        }

        .course-detail-hero,
        .learning-sequence-header,
        .quiz-transition,
        .url-analyzer-hero,
        .simulation-library-hero {
            background: linear-gradient(112deg, var(--zap-surface) 0%, var(--zap-surface-tint) 100%);
            box-shadow: 0 0.8rem 1.8rem rgb(18 58 55 / 10%);
        }

        .simulation-hero {
            border-left-color: var(--zap-primary);
            background: linear-gradient(112deg, var(--zap-surface) 0%, var(--zap-surface-tint) 100%);
        }

        .course-detail-hero {
            border-left-color: var(--zap-primary);
        }

        .learning-sequence-header,
        .simulation-library-hero,
        .url-analyzer-hero {
            border-top-color: var(--zap-primary);
        }

        .course-lesson-number {
            background: rgb(30 101 89 / 10%);
            color: var(--zap-deep);
        }

        .course-lesson-card {
            border-top-color: var(--zap-primary);
        }

        .course-lesson-number {
            background: rgb(30 101 89 / 10%);
        }

        .simulation-card {
            border-top-color: var(--zap-cta);
        }

        .simulation-card-icon {
            border-color: var(--zap-border);
            background: var(--zap-surface-tint);
        }

        .simulation-step-panel.simulation-briefing {
            border-left-color: var(--zap-primary);
            background: linear-gradient(112deg, var(--zap-surface), var(--zap-surface-tint));
        }

        .simulation-step-panel .progress {
            border-color: rgb(18 58 55 / 12%);
            background-color: #e2eae1;
            background-image: repeating-linear-gradient(
                90deg,
                transparent 0 calc(20% - 1px),
                rgb(18 58 55 / 15%) calc(20% - 1px) 20%
            );
        }

        .simulation-step-panel .progress-bar {
            background: linear-gradient(90deg, var(--zap-primary), var(--zap-deep));
            box-shadow: 0 0 0.7rem rgb(30 101 89 / 25%);
        }

        .simulation-step-markers span {
            background: #cbd8cf;
        }

        .simulation-scenario-card {
            border-top-color: var(--zap-primary);
            background: linear-gradient(155deg, var(--zap-surface), var(--zap-surface-tint));
        }

        .simulation-choice:hover,
        .simulation-choice:focus-within {
            border-color: var(--zap-primary) !important;
            background: rgb(30 101 89 / 5%);
            box-shadow: 0 0.25rem 0.8rem rgb(18 58 55 / 8%);
        }

        .simulation-completion-hero,
        .simulation-score-card {
            border-color: rgb(237 189 89 / 48%);
            background: linear-gradient(145deg, var(--zap-surface), #fbf5e8);
        }

        .simulation-score-card {
            background: var(--zap-surface);
        }

        .simulation-choice:hover,
        .simulation-choice:focus-within {
            border-color: var(--zap-primary) !important;
            background: rgb(30 101 89 / 5%);
            box-shadow: 0 0.25rem 0.8rem rgb(18 58 55 / 8%);
        }

        .simulation-reflection-item {
            background: var(--zap-surface);
        }

        .lesson-objective,
        .simulation-outcome {
            background-color: var(--zap-surface-tint);
        }

        .achievement-card {
            background-color: #fbf7ed;
        }

        .url-analyzer-guidance,
        .url-analyzer-form-card,
        .url-analyzer-hero-mark,
        .risk-result-header,
        .risk-result-level,
        .risk-findings-panel {
            background-color: var(--zap-surface);
        }

        .url-analyzer-form-card {
            border-top-color: var(--zap-primary);
        }

        .url-analyzer-hero-mark {
            border-color: rgb(30 101 89 / 18%);
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgb(30 101 89 / 17%);
        }

        .quiz-transition-cta {
            box-shadow: 0 0.35rem 0.9rem rgb(18 58 55 / 18%);
        }

        .risk-level {
            box-shadow: var(--zap-shadow);
        }

        .risk-findings-list li {
            border-color: rgb(30 101 89 / 14%);
            background: var(--zap-surface-tint);
        }

        .risk-findings-list li::before {
            background: var(--zap-primary);
        }

        .risk-recommendation {
            background: linear-gradient(145deg, var(--zap-surface), #fbf5e8);
        }

        .site-navbar {
            border-bottom-color: rgb(255 255 255 / 16%);
        }

        .brand-mark {
            background: var(--zap-primary);
            color: #fff;
        }

        .site-navbar .navbar-nav .nav-link:hover,
        .site-navbar .navbar-nav .nav-link:focus-visible,
        .site-navbar .navbar-nav .nav-link.active {
            box-shadow: inset 0 -2px 0 #8BC3A9;
        }

        .site-navbar .navbar-nav .nav-link:focus-visible,
        .site-navbar .navbar-brand:focus-visible,
        .site-navbar .navbar-toggler:focus-visible,
        .btn:focus-visible {
            outline-color: var(--zap-cta);
        }

        .quick-action-card {
            min-height: 12rem;
            gap: 1.1rem;
            padding: 1.35rem;
            border-top-width: 3px;
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .quick-action-card:hover,
        .quick-action-card:focus-visible {
            border-color: var(--zap-border);
            border-top-color: var(--zap-primary);
            box-shadow: 0 0.6rem 1.35rem rgb(18 58 55 / 12%);
            transform: translateY(-2px);
        }

        .quick-action-card-analyzer {
            border-top-color: var(--zap-primary);
            background: var(--zap-surface);
        }

        .quick-action-label {
            color: var(--zap-muted);
            font-size: 0.7rem;
            letter-spacing: 0.06em;
        }

        .quick-action-icon {
            width: 3.25rem;
            height: 3.25rem;
            border-color: var(--zap-border);
            border-radius: 0.75rem;
            background: var(--zap-surface-tint);
            color: var(--zap-primary);
            box-shadow: none;
        }

        .quick-action-icon-shield::before {
            width: 1.45rem;
            height: 1.65rem;
        }

        .quick-action-icon-shield::after {
            top: 1.28rem;
            left: 1.2rem;
            width: 0.85rem;
            height: 0.45rem;
        }

        .quick-action-icon-search::before {
            width: 1.25rem;
            height: 1.25rem;
            border-width: 2px;
        }

        .quick-action-icon-search::after {
            top: 1.83rem;
            left: 1.82rem;
            width: 0.7rem;
            border-top-width: 2px;
        }

        .course-card,
        .simulation-card {
            border-top-color: var(--zap-primary);
            background: var(--zap-surface);
            background-image: none;
        }

        .course-level-badge:not(.course-level-intermediate):not(.course-level-advanced) {
            border-color: var(--zap-border);
            background: var(--zap-surface-tint);
            color: var(--zap-deep);
        }

        .course-level-intermediate {
            border-color: var(--zap-primary);
            background: var(--zap-primary);
            color: #fff;
        }

        .course-level-advanced {
            border-color: var(--zap-deep);
            background: var(--zap-deep);
            color: #fff;
        }

        .simulation-card-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-color: var(--zap-border);
            border-radius: 0.75rem;
            background: var(--zap-surface-tint);
        }

        .simulation-library-count {
            border-color: var(--zap-border);
            background: var(--zap-surface);
        }

        .simulation-progress-percent {
            border-color: var(--zap-primary);
        }

        .simulation-step-panel .progress-bar {
            background: var(--zap-primary);
            box-shadow: none;
        }

        .simulation-step-markers span.is-reached {
            background: var(--zap-primary);
            box-shadow: none;
        }

        .simulation-scenario-card {
            background: var(--zap-surface);
        }

        .simulation-scenario-text {
            border-left-color: var(--zap-primary);
            background: var(--zap-surface-tint);
        }

        .course-detail-hero,
        .learning-sequence-header,
        .quiz-transition,
        .url-analyzer-hero,
        .simulation-library-hero {
            background: var(--zap-surface);
            box-shadow: var(--zap-shadow);
        }

        .course-detail-hero {
            border-left-color: var(--zap-primary);
        }

        .learning-sequence-header {
            border-top-color: var(--zap-primary);
        }

        .quiz-transition {
            border-left-color: var(--zap-primary);
        }

        .url-analyzer-hero,
        .simulation-library-hero {
            border-top-color: var(--zap-primary);
        }

        .url-analyzer-guidance {
            border-left-color: var(--zap-primary);
        }

        .url-analyzer-form-card {
            background: var(--zap-surface);
        }

        .risk-findings-list li {
            background: var(--zap-surface-tint);
        }

        .risk-recommendation {
            border-left-color: var(--zap-primary);
            background: var(--zap-surface-tint);
        }

        .lesson-objective {
            border-color: var(--zap-border);
            border-left-color: var(--zap-primary);
            background: var(--zap-surface);
        }

        .simulation-outcome,
        .achievement-card,
        .simulation-completion-hero,
        .simulation-score-card {
            background: #fbf7ed;
        }

        .simulation-score-card {
            background: var(--zap-surface);
        }

        .dashboard-stat.stat-black {
            border-top-color: var(--zap-deep);
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
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
                        <a class="nav-link <?= ($activePage ?? '') === 'quizzes' ? 'active' : ''; ?>"
                            aria-current="<?= ($activePage ?? '') === 'quizzes' ? 'page' : 'false'; ?>"
                            href="?page=quizzes">Quizzes</a>
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
                        <a class="nav-link <?= ($activePage ?? '') === 'url-analyzer' ? 'active' : ''; ?>"
                            aria-current="<?= ($activePage ?? '') === 'url-analyzer' ? 'page' : 'false'; ?>"
                            href="?page=url-analyzer">URL Analyzer</a>
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