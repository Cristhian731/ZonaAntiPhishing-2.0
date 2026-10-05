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

    public function getScenarios(int $simulationId): array
    {
        return $this->simulationModel->getScenariosBySimulationId($simulationId);
    }

    public function submitSimulation(int $userId, int $simulationId, array $answers): ?array
    {
        if ($userId < 1 || $simulationId < 1 || count($answers) !== 5) {
            return null;
        }

        $simulation = $this->simulationModel->getSimulationById($simulationId);
        $scenarios = $this->simulationModel->getScenariosBySimulationId($simulationId);

        if (!$simulation || count($scenarios) !== 5) {
            return null;
        }

        $expectedScenarioIds = array_map(
            static fn(array $scenario): int => (int) ($scenario['id'] ?? 0),
            $scenarios
        );

        foreach (array_keys($answers) as $scenarioId) {
            if (!in_array((int) $scenarioId, $expectedScenarioIds, true)) {
                return null;
            }
        }

        $correctCount = 0;
        $decisions = [];

        foreach ($scenarios as $scenario) {
            $scenarioId = (int) ($scenario['id'] ?? 0);
            $submittedAnswer = $answers[$scenarioId] ?? null;

            if (!is_string($submittedAnswer)) {
                return null;
            }

            $answer = strtolower(trim($submittedAnswer));

            if (!in_array($answer, ['phishing', 'legitimate'], true)) {
                return null;
            }

            $correctAnswer = (string) ($scenario['correct_answer'] ?? '');
            $isCorrect = $answer === $correctAnswer;
            $correctCount += (int) $isCorrect;
            $decisions[] = [
                'step_order' => (int) ($scenario['step_order'] ?? 0),
                'correct' => $isCorrect,
                'answer' => $answer,
                'correct_answer' => $correctAnswer,
                'explanation' => (string) ($scenario['explanation'] ?? ''),
            ];
        }

        $score = (int) round(($correctCount / count($scenarios)) * 100);
        $passed = $correctCount >= 3;

        $attemptSaved = $this->simulationModel->saveSimulationAttempt(
            $userId,
            $simulationId,
            $score,
            $passed,
            2
        );

        if (!$attemptSaved) {
            return null;
        }

        return [
            'correct_count' => $correctCount,
            'total_scenarios' => count($scenarios),
            'passed' => $passed,
            'score' => $score,
            'decisions' => $decisions,
        ];
    }
}
