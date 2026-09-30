-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-09-2026 a las 18:48:50
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zona_antiphishing`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `level` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `level`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Introduction to Phishing', 'Learn the fundamentals of phishing attacks and how to identify them.', 'beginner', 'published', '2026-09-30 14:53:42', '2026-09-30 14:53:42'),
(2, 'Types of Phishing', 'Explore the different phishing techniques used by cybercriminals.', 'beginner', 'published', '2026-09-30 14:53:42', '2026-09-30 14:53:42'),
(3, 'Real World Cases', 'Analyze real phishing incidents and their consequences.', 'intermediate', 'published', '2026-09-30 14:53:42', '2026-09-30 14:53:42'),
(4, 'Digital Protection', 'Learn best practices for protecting personal and organizational information.', 'intermediate', 'published', '2026-09-30 14:53:42', '2026-09-30 14:53:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` longtext NOT NULL,
  `lesson_order` int(11) NOT NULL,
  `estimated_minutes` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `content`, `lesson_order`, `estimated_minutes`, `created_at`, `updated_at`) VALUES
(1, 1, 'What is Phishing?', 'Introduction to phishing attacks, their objectives and how they affect users.', 1, 10, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(2, 1, 'Common Phishing Indicators', 'Learn how to identify suspicious emails, links and messages.', 2, 15, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(3, 1, 'How Attackers Operate', 'Understand the most common phishing techniques used by cybercriminals.', 3, 15, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(4, 2, 'Email Phishing', 'Learn how phishing emails are structured and delivered.', 1, 15, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(5, 2, 'Smishing', 'Understand phishing attacks delivered through SMS messages.', 2, 10, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(6, 2, 'Spear Phishing', 'Learn how targeted phishing attacks work.', 3, 20, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(7, 3, 'Banking Fraud Case', 'Analysis of a real phishing incident targeting banking customers.', 1, 20, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(8, 3, 'Fake Social Media Login', 'Case study involving social media credential theft.', 2, 15, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(9, 4, 'Password Security', 'Best practices for creating and managing passwords.', 1, 15, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(10, 4, 'Multi-Factor Authentication', 'How MFA helps protect online accounts.', 2, 15, '2026-09-30 15:05:19', '2026-09-30 15:05:19'),
(11, 4, 'Safe Browsing Habits', 'Practical recommendations for safer internet usage.', 3, 15, '2026-09-30 15:05:19', '2026-09-30 15:05:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `passing_score` int(11) NOT NULL DEFAULT 70,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `quizzes`
--

INSERT INTO `quizzes` (`id`, `lesson_id`, `title`, `description`, `passing_score`, `created_at`, `updated_at`) VALUES
(1, 1, 'Introduction to Phishing Quiz', 'Basic assessment about phishing fundamentals.', 70, '2026-09-30 15:16:14', '2026-09-30 15:16:14'),
(2, 4, 'Types of Phishing Quiz', 'Assessment focused on phishing variants.', 70, '2026-09-30 15:16:14', '2026-09-30 15:16:14'),
(3, 7, 'Real Cases Quiz', 'Assessment based on real phishing incidents.', 70, '2026-09-30 15:16:14', '2026-09-30 15:16:14'),
(4, 9, 'Digital Protection Quiz', 'Assessment about security best practices.', 70, '2026-09-30 15:16:14', '2026-09-30 15:16:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `user_id`, `quiz_id`, `score`, `passed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 50, 0, '2026-09-30 15:26:31', '2026-09-30 15:26:31', '2026-09-30 15:26:31'),
(2, 1, 2, 100, 1, '2026-09-30 15:26:48', '2026-09-30 15:26:48', '2026-09-30 15:26:48'),
(3, 1, 1, 100, 1, '2026-09-30 15:33:13', '2026-09-30 15:33:13', '2026-09-30 15:33:13'),
(4, 1, 1, 100, 1, '2026-09-30 15:40:04', '2026-09-30 15:40:04', '2026-09-30 15:40:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz_options`
--

CREATE TABLE `quiz_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `quiz_options`
--

INSERT INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`, `created_at`) VALUES
(1, 1, 'A cybersecurity attack that tricks users into revealing information', 1, '2026-09-30 15:19:21'),
(2, 1, 'A type of antivirus software', 0, '2026-09-30 15:19:21'),
(3, 1, 'A network firewall', 0, '2026-09-30 15:19:21'),
(4, 1, 'A secure email protocol', 0, '2026-09-30 15:19:21'),
(5, 2, 'Suspicious links', 1, '2026-09-30 15:19:21'),
(6, 2, 'Official company logos', 0, '2026-09-30 15:19:21'),
(7, 2, 'Encrypted attachments only', 0, '2026-09-30 15:19:21'),
(8, 2, 'Strong passwords', 0, '2026-09-30 15:19:21'),
(9, 3, 'Phishing through SMS messages', 1, '2026-09-30 15:19:21'),
(10, 3, 'Phishing through phone calls', 0, '2026-09-30 15:19:21'),
(11, 3, 'Phishing through websites only', 0, '2026-09-30 15:19:21'),
(12, 3, 'A password manager', 0, '2026-09-30 15:19:21'),
(13, 4, 'They help users recognize real attack patterns', 1, '2026-09-30 15:19:21'),
(14, 4, 'They replace security software', 0, '2026-09-30 15:19:21'),
(15, 4, 'They guarantee protection', 0, '2026-09-30 15:19:21'),
(16, 4, 'They eliminate phishing completely', 0, '2026-09-30 15:19:21'),
(17, 5, 'Multi-Factor Authentication', 1, '2026-09-30 15:19:21'),
(18, 5, 'Multiple File Access', 0, '2026-09-30 15:19:21'),
(19, 5, 'Managed Firewall Application', 0, '2026-09-30 15:19:21'),
(20, 5, 'Modern Fraud Alert', 0, '2026-09-30 15:19:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `explanation`, `created_at`, `updated_at`) VALUES
(1, 1, 'What is phishing?', 'Phishing is a social engineering attack used to steal information.', '2026-09-30 15:17:53', '2026-09-30 15:17:53'),
(2, 1, 'Which element is commonly found in phishing emails?', 'Suspicious links are one of the most common indicators.', '2026-09-30 15:17:53', '2026-09-30 15:17:53'),
(3, 2, 'What is smishing?', 'Smishing is phishing conducted through SMS messages.', '2026-09-30 15:17:53', '2026-09-30 15:17:53'),
(4, 3, 'Why are real phishing cases important?', 'They help users recognize real attack patterns.', '2026-09-30 15:17:53', '2026-09-30 15:17:53'),
(5, 4, 'What does MFA stand for?', 'Multi-Factor Authentication.', '2026-09-30 15:17:53', '2026-09-30 15:17:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `simulations`
--

CREATE TABLE `simulations` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `difficulty` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `simulations`
--

INSERT INTO `simulations` (`id`, `title`, `description`, `difficulty`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Fake Bank Email', 'Learn how to identify fraudulent banking emails.', 'beginner', 'published', '2026-09-30 15:53:38', '2026-09-30 15:53:38'),
(2, 'Suspicious SMS Message', 'Recognize phishing attempts delivered through SMS.', 'beginner', 'published', '2026-09-30 15:53:38', '2026-09-30 15:53:38'),
(3, 'Fake Social Media Login', 'Identify credential theft attempts using fake login pages.', 'intermediate', 'published', '2026-09-30 15:53:38', '2026-09-30 15:53:38'),
(4, 'Corporate Email Scam', 'Detect advanced phishing attacks targeting employees.', 'advanced', 'published', '2026-09-30 15:53:38', '2026-09-30 15:53:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `simulation_attempts`
--

CREATE TABLE `simulation_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `simulation_id` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `simulation_attempts`
--

INSERT INTO `simulation_attempts` (`id`, `user_id`, `simulation_id`, `score`, `passed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100, 1, '2026-09-30 16:01:23', '2026-09-30 16:01:23', '2026-09-30 16:01:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `simulation_scenarios`
--

CREATE TABLE `simulation_scenarios` (
  `id` int(11) NOT NULL,
  `simulation_id` int(11) NOT NULL,
  `scenario_text` longtext NOT NULL,
  `correct_answer` enum('phishing','legitimate') NOT NULL DEFAULT 'phishing',
  `explanation` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `simulation_scenarios`
--

INSERT INTO `simulation_scenarios` (`id`, `simulation_id`, `scenario_text`, `correct_answer`, `explanation`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bank XYZ: We detected suspicious activity in your account. Click immediately on www.bank-verification.xyz to avoid suspension.', 'phishing', 'The scenario uses urgency and a suspicious URL that does not match the official bank domain.', '2026-09-30 15:55:29', '2026-09-30 15:55:29'),
(2, 2, 'SMS: Your package could not be delivered. Confirm your information at www.delivery-support.xyz', 'phishing', 'The message uses a fake delivery notification and redirects the user to a suspicious website.', '2026-09-30 15:55:29', '2026-09-30 15:55:29'),
(3, 3, 'Social Network: Your account has been restricted. Sign in again using the following page: www.social-login-security.xyz', 'phishing', 'The page imitates a social media login to steal credentials.', '2026-09-30 15:55:29', '2026-09-30 15:55:29'),
(4, 4, 'Corporate Email: Finance department requests an urgent transfer to a new supplier account without following normal procedures.', 'phishing', 'The message attempts to bypass company procedures and create urgency.', '2026-09-30 15:55:29', '2026-09-30 15:55:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `is_active`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'Cristhian', 'prueba@test.com', '$2y$10$sGxm/J7qmviFR8dUT8nLB.jEGAlE8ZfRkl9P/0vge7C/v5Gbci9I2', 'user', 1, '2026-09-30 13:41:26', '2026-09-30 13:41:26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_progress`
--

INSERT INTO `user_progress` (`id`, `user_id`, `lesson_id`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-09-30 15:40:04', '2026-09-30 15:40:04', '2026-09-30 15:40:04');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indices de la tabla `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indices de la tabla `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indices de la tabla `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indices de la tabla `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indices de la tabla `simulations`
--
ALTER TABLE `simulations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `simulation_attempts`
--
ALTER TABLE `simulation_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`simulation_id`),
  ADD KEY `fk_sim_attempt_simulation` (`simulation_id`);

--
-- Indices de la tabla `simulation_scenarios`
--
ALTER TABLE `simulation_scenarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simulation_id` (`simulation_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indices de la tabla `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `quiz_options`
--
ALTER TABLE `quiz_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `simulations`
--
ALTER TABLE `simulations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `simulation_attempts`
--
ALTER TABLE `simulation_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `simulation_scenarios`
--
ALTER TABLE `simulation_scenarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `fk_lessons_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `fk_quiz_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `fk_attempt_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_attempt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD CONSTRAINT `fk_option_question` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `fk_question_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `simulation_attempts`
--
ALTER TABLE `simulation_attempts`
  ADD CONSTRAINT `fk_sim_attempt_simulation` FOREIGN KEY (`simulation_id`) REFERENCES `simulations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sim_attempt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `simulation_scenarios`
--
ALTER TABLE `simulation_scenarios`
  ADD CONSTRAINT `fk_scenario_simulation` FOREIGN KEY (`simulation_id`) REFERENCES `simulations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `fk_progress_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
