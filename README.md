# Zona AntiPhishing 2.0

## Why Zona AntiPhishing?

Most cybersecurity resources only provide theory.

Zona AntiPhishing helps users:

- Learn through structured educational content.
- Practice through quizzes and simulations.
- Analyze suspicious URLs.
- Earn digital certificates.

The platform combines learning, practice, analysis and certification in a single educational ecosystem.

## Learn. Practice. Analyze. Protect.

Zona AntiPhishing 2.0 is an educational platform for phishing prevention and analysis. It combines guided course content, quizzes, practical simulations, URL warning-sign analysis, progress tracking, and course-completion certificates in one learning experience.

> Zona AntiPhishing is a learning platform, not an antivirus, email filter, or guarantee that a website is safe.

## Project Overview

Phishing relies on convincing messages and pressure to make people share information, approve an action, or trust a misleading request. The platform helps learners build practical habits for recognizing these situations before responding.

The MVP serves individual learners through a public landing page and authenticated learning areas. Teachers, evaluators, and organizations can explore the educational flow, but the current product does not include classroom administration, cohort management, or organization-wide reporting.

## Problem Statement

People encounter suspicious messages in personal, academic, and workplace settings. Many are unsure how to assess the sender, the request, or a link, especially when a message creates urgency or appears familiar. Theory alone may not prepare learners to make a careful decision in the moment.

## Solution

Zona AntiPhishing presents phishing prevention as a practical learning path:

- Learn concepts and warning signs through courses and lesson content.
- Practice decisions with quizzes and phishing scenarios.
- Analyze common warning signs in a URL using a local, rule-based analyzer.
- Track learning activity and receive a certificate after completing a course.

The platform does not fetch or reputation-check submitted URLs against external services. Its analyzer is an educational aid and cannot establish that a URL is safe.

## Value Proposition

Zona AntiPhishing helps students, professionals, and organizations recognize, practice, analyze, and prevent phishing attacks through interactive learning, realistic simulations, URL analysis, and digital certifications.

## Core Features

- **Interactive Courses:** browse available courses, descriptions, and difficulty levels.
- **Lesson Content:** read lesson material, optional summaries, estimated duration, and navigate lessons within a course.
- **Quiz Engine:** answer lesson quizzes, receive a score, and see whether the passing threshold was reached.
- **Realistic Simulations:** classify phishing scenarios and receive explanatory feedback.
- **URL Analyzer:** review common URL warning signs, including missing HTTPS, IP-based hosts, `@` characters, long URLs, deep paths, and selected link-shortener domains. It is not a reputation service or safety guarantee.
- **Progress Tracking:** record completed lessons and show learning metrics on the dashboard and profile.
- **Profile:** view basic account information and personal learning metrics.
- **Certificates:** automatically issue a course-completion certificate when the course requirements are met.
- **PDF Export:** download a generated PDF certificate.
- **CSRF Protection:** validate session-bound tokens on Login, Register, Quiz, Simulation, and URL Analyzer POST forms.

## User Flow

The public and account flow is:

```text
Landing Page → Register → Login → Dashboard
```

From the dashboard, learners can open courses, simulations, and the URL Analyzer. The course learning path is:

```text
Courses → Course Details → Lesson Content → Quiz
```

Passing quizzes records lesson completion. When all lessons in a course are complete, the learner can access the certificate and download its PDF. Simulations and URL analysis are separate practice tools; they are not prerequisites for a certificate.

## Target Customers

- **Students:** build safer digital habits through guided material and practice.
- **Professionals:** improve recognition of suspicious workplace communications.
- **Small Businesses:** introduce phishing awareness practice for small teams.
- **Educational Institutions:** use a structured learning experience to support cybersecurity education.

These are intended audiences. The current MVP does not provide institution-specific administration or team reporting.

## Competitive Advantage

Zona AntiPhishing brings five parts of a learning experience together:

- **Learn:** courses and lessons introduce concepts.
- **Practice:** quizzes and simulations let learners apply them.
- **Analyze:** the URL Analyzer explains selected structural warning signs.
- **Protect:** the goal is to encourage safer decisions and verification habits.
- **Certify:** course completion can be recognized with a downloadable certificate.

The differentiator is this combination in one platform, rather than a claim that the product blocks or removes phishing threats.

## Business Model

The following are possible business models for future evaluation; billing, subscriptions, and license management are not implemented in the current MVP:

- Freemium access
- Premium courses
- Digital certificates
- Business licenses
- Educational agreements

## Technology Stack

- PHP 8+
- MySQL-compatible database (MySQL or MariaDB) through PDO
- Bootstrap 5.3
- XAMPP for local development
- Git and GitHub for version control and project hosting

Bootstrap assets and the landing/authentication imagery are loaded from external CDNs. No Composer package is required for the current PDF generator.

## Getting Started

### Requirements

- PHP 8.0 or later with PDO MySQL enabled
- MySQL or MariaDB
- Apache or another PHP-capable web server; XAMPP is suitable for local development
- A browser with access to the Bootstrap CDN for the intended styling

### Database Setup

1. Start Apache and MySQL/MariaDB in XAMPP.
2. Create a database named `zona_antiphishing` using `utf8mb4` character encoding.
3. Import [`docs/database/sprint10_certificates_complete.sql`](docs/database/sprint10_certificates_complete.sql) into that database. This dump creates the tables and includes development seed data; it does not create the database itself.
4. Apply [`docs/database/sprint11_lesson_summary.sql`](docs/database/sprint11_lesson_summary.sql) after the dump. It adds the nullable `lessons.summary` column and updates the seeded lessons with educational content.
5. Apply [`docs/database/sprint12_educational_content.sql`](docs/database/sprint12_educational_content.sql) after Sprint 11 to install the complete lesson content, summaries, and quizzes. Back up the database first; the migration preserves existing quiz questions, options, and attempts.
6. Set the local database host, database name, username, and password in [`config/database.php`](config/database.php) to match your environment.

The application currently reads database settings directly from [`config/database.php`](config/database.php); it does not load a `.env` file or run migrations automatically. Keep deployment credentials out of public repositories and do not use development database defaults in production.

### Run Locally

Place the project under the web server's document root and open its `public` directory. For a typical XAMPP installation, the URL will look like:

```text
http://localhost/ZonaAntiPhishing-2.0/public/
```

The actual path depends on the folder name and where the project is installed. The front controller is [`public/index.php`](public/index.php).

## Application Routes

Routes use the `page` query parameter:

| Route                           | Purpose                                                              |
| ------------------------------- | -------------------------------------------------------------------- |
| `?page=home`                    | Public landing page; also the default for visitors without a session |
| `?page=register`                | Create an account                                                    |
| `?page=login`                   | Sign in                                                              |
| `?page=dashboard`               | View learning metrics                                                |
| `?page=courses`                 | Browse courses                                                       |
| `?page=course&id={id}`          | View course details and lessons                                      |
| `?page=lesson&id={id}`          | Read lesson content and navigate within a course                     |
| `?page=quiz&lesson_id={id}`     | Take the quiz attached to a lesson                                   |
| `?page=quizzes`                 | Review available quizzes and personal status                         |
| `?page=simulations`             | Browse phishing simulations                                          |
| `?page=simulation&id={id}`      | Complete a simulation scenario                                       |
| `?page=url-analyzer`            | Analyze URL warning signs                                            |
| `?page=certificates`            | View earned certificates                                             |
| `?page=certificate&id={id}`     | View a certificate                                                   |
| `?page=certificate-pdf&id={id}` | Download a certificate PDF                                           |
| `?page=profile`                 | View account details and progress                                    |
| `?page=logout`                  | End the current session                                              |

Authenticated users who open the site root are sent to the dashboard. Learning and account routes require a session.

## Current MVP Status

The following modules are implemented in the current application:

- Public landing page and registration/login flow
- Session-protected dashboard and profile
- Course catalog, course details, lesson content, summaries, and lesson navigation
- Quiz taking, attempt scoring, status listing, and lesson completion tracking
- Phishing simulation scenarios with answer feedback
- Local rule-based URL analysis
- Automatic course certificate generation and certificate listing/details
- PHP-generated certificate PDF download
- CSRF token validation for the five application POST forms

The lesson summary migration must be applied for summaries to appear in a database created from an earlier dump. The `tests/` directory does not currently contain an automated test suite; use manual checks and PHP syntax validation during development.

## Future Roadmap

- **Security Improvements:** continue reviewing production configuration, session handling, validation, and deployment practices.
- **Gamification:** evaluate milestones and learner engagement features.
- **Administrative Panel:** manage learning content and operational needs.
- **Advanced Analytics:** provide deeper learning insights where appropriate.

These items are planned opportunities, not current capabilities.

## Project Structure

```text
app/
	controllers/   Request and application-flow controllers
	models/        PDO-backed data access and domain operations
	services/      Reusable services, including CSRF protection
config/          Database connection configuration
docs/database/   Database dumps and SQL migrations
public/          Front controller and public entry point
views/           Authentication, learning, and account pages
```

## Authors

- Cristian Steven Aza Diaz
- Luis German De La Rosa Altamiranda

## License

See the [`LICENSE`](LICENSE) file for the project license.
