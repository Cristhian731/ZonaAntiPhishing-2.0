<?php

require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController
{
    private Dashboard $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new Dashboard();
    }

    public function getMetricsForUser(int $userId): array
    {
        $metrics = $this->dashboardModel->getMetricsForUser($userId);
        $lessonsCompleted = (int) ($metrics['lessons_completed'] ?? 0);
        $totalLessons = (int) ($metrics['total_lessons'] ?? 0);

        return [
            'courses_available' => (int) ($metrics['courses_available'] ?? 0),
            'lessons_completed' => $lessonsCompleted,
            'quizzes_passed' => (int) ($metrics['quizzes_passed'] ?? 0),
            'progress_percent' => $totalLessons > 0
                ? round(($lessonsCompleted / $totalLessons) * 100)
                : 0,
        ];
    }
}
