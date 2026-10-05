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
            'courses_available' =>
                (int) ($metrics['courses_available'] ?? 0),

            'lessons_completed' =>
                $lessonsCompleted,

            'quizzes_passed' =>
                (int) ($metrics['quizzes_passed'] ?? 0),

            'simulations_passed' =>
                (int) ($metrics['simulations_passed'] ?? 0),

            'progress_percent' =>
                $totalLessons > 0
                ? round(($lessonsCompleted / $totalLessons) * 100)
                : 0,
        ];
    }

    public function getLearningHubForUser(int $userId): array
    {
        if ($userId < 1) {
            return ['state' => 'new'];
        }

        $learningHub = $this->dashboardModel->getLearningHubForUser($userId);
        $state = (string) ($learningHub['state'] ?? 'new');

        if (!in_array($state, ['new', 'active', 'completed'], true)) {
            $state = 'new';
        }

        return [
            'state' => $state,
            'course_id' => (int) ($learningHub['course_id'] ?? 0),
            'course_title' => (string) ($learningHub['course_title'] ?? ''),
            'lesson_id' => (int) ($learningHub['lesson_id'] ?? 0),
            'lesson_title' => (string) ($learningHub['lesson_title'] ?? ''),
            'course_completed' => (int) ($learningHub['course_completed'] ?? 0),
            'course_total' => (int) ($learningHub['course_total'] ?? 0),
            'course_progress_percent' => max(0, min(100, (int) ($learningHub['course_progress_percent'] ?? 0))),
            'completed_lessons' => (int) ($learningHub['completed_lessons'] ?? 0),
            'total_lessons' => (int) ($learningHub['total_lessons'] ?? 0),
        ];
    }
}
