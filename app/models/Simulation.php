<?php

require_once __DIR__ . '/../../config/database.php';

class Simulation
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllSimulations()
    {
        $sql = "SELECT * FROM simulations ORDER BY id ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSimulationById(int $id)
    {
        $sql = "SELECT * FROM simulations WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getScenarioBySimulationId(int $simulationId)
    {
        $sql = "SELECT * FROM simulation_scenarios
                WHERE simulation_id = :simulation_id
                ORDER BY id ASC
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':simulation_id', $simulationId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveSimulationAttempt(int $userId, int $simulationId, int $score, bool $passed): bool
    {
        $sql = "INSERT INTO simulation_attempts (user_id, simulation_id, score, passed, completed_at)
                VALUES (:user_id, :simulation_id, :score, :passed, CURRENT_TIMESTAMP)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'simulation_id' => $simulationId,
            'score' => $score,
            'passed' => (int) $passed,
        ]);
    }
}
