-- Sprint 14: persistent login-attempt throttling.
-- Apply once to the zona_antiphishing database before enabling the login throttle.

CREATE TABLE IF NOT EXISTS `login_attempts` (
    `attempt_key` CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    `failed_attempts` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `first_attempt_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `blocked_until` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`attempt_key`),
    KEY `idx_login_attempts_first_attempt_at` (`first_attempt_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
