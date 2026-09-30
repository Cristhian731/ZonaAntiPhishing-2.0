<?php

require_once __DIR__ . '/../models/Simulation.php';

class SimulationController
{
    private Simulation $simulationModel;

    public function __construct()
    {
        $this->simulationModel = new Simulation();
    }

    public function getSimulations()
    {
        return $this->simulationModel->getAllSimulations();
    }

    public function getSimulation(int $id)
    {
        return $this->simulationModel->getSimulationById($id);
    }

    public function getScenario(int $simulationId)
    {
        return $this->simulationModel->getScenarioBySimulationId($simulationId);
    }

    public function submitSimulation(int $userId, int $simulationId, string $answer): ?array
    {
        if ($userId < 1 || $simulationId < 1) {
            return null;
        }

        $answer = strtolower(trim($answer));

        if (!in_array($answer, ['phishing', 'legitimate'], true)) {
            return null;
        }

        $simulation = $this->simulationModel->getSimulationById($simulationId);
        $scenario = $this->simulationModel->getScenarioBySimulationId($simulationId);

        if (!$simulation || !$scenario) {
            return null;
        }

        $correctAnswer = (string) ($scenario['correct_answer'] ?? '');
        $isCorrect = $answer === $correctAnswer;
        $score = $isCorrect ? 100 : 0;

        $attemptSaved = $this->simulationModel->saveSimulationAttempt(
            $userId,
            $simulationId,
            $score,
            $isCorrect
        );

        if (!$attemptSaved) {
            return null;
        }

        return [
            'correct' => $isCorrect,
            'answer' => $answer,
            'correct_answer' => $correctAnswer,
            'explanation' => (string) ($scenario['explanation'] ?? ''),
            'score' => $score,
        ];
    }
}
