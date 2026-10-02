<?php

require_once __DIR__ . '/../models/Quiz.php';

class QuizController
{
    private Quiz $quizModel;

    public function __construct()
    {
        $this->quizModel = new Quiz();
    }

    public function getQuizzesForUser(int $userId): array
    {
        if ($userId < 1) {
            return [];
        }

        return $this->quizModel->getQuizzesForUser($userId);
    }

    public function getQuizForLesson(int $lessonId)
    {
        $quiz = $this->quizModel->getQuizByLessonId($lessonId);

        if (!$quiz) {
            return null;
        }

        $questions = $this->quizModel->getQuestionsByQuizId((int) $quiz['id']);

        foreach ($questions as &$question) {
            $question['options'] = $this->quizModel->getOptionsByQuestionId((int) $question['id']);
        }
        unset($question);

        $quiz['questions'] = $questions;

        return $quiz;
    }

    public function submitQuiz(int $userId, int $lessonId, array $answers)
    {
        if ($userId < 1 || $lessonId < 1) {
            return null;
        }

        $quiz = $this->quizModel->getQuizByLessonId($lessonId);

        if (!$quiz) {
            return null;
        }

        $quizId = (int) $quiz['id'];
        $questions = $this->quizModel->getQuestionsByQuizId($quizId);
        $totalQuestions = count($questions);

        if ($totalQuestions === 0) {
            return null;
        }

        $correctAnswers = 0;

        foreach ($questions as $question) {
            $questionId = (int) $question['id'];
            $submittedOption = $answers[$questionId] ?? null;

            if (!is_scalar($submittedOption)) {
                continue;
            }

            $selectedOptionId = filter_var($submittedOption, FILTER_VALIDATE_INT);

            if ($selectedOptionId === false || $selectedOptionId < 1) {
                continue;
            }

            $options = $this->quizModel->getOptionsByQuestionId($questionId);

            foreach ($options as $option) {
                if ((int) $option['id'] === $selectedOptionId && (int) $option['is_correct'] === 1) {
                    $correctAnswers++;
                    break;
                }
            }
        }

        $score = (int) round(($correctAnswers / $totalQuestions) * 100);
        $passingScore = max(0, min(100, (int) ($quiz['passing_score'] ?? 70)));
        $passed = $score >= $passingScore;

        $attemptSaved = $this->quizModel->saveQuizAttempt($userId, $quizId, $score, $passed);

        if ($attemptSaved && $passed) {
            require_once __DIR__ . '/../models/UserProgress.php';

            $userProgress = new UserProgress();
            $userProgress->markLessonCompleted($userId, $lessonId);
        }

        return [
            'score' => $score,
            'passed' => $passed,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
        ];
    }
}
