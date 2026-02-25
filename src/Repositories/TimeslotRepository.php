<?php

namespace App\Repositories;

use App\Models\TimeslotModel;
use PDO;

class TimeslotRepository extends Repository implements ITimeslotRepository
{
    public function getByCourtId(int $courtId): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT id, court_id, start_time, end_time
                FROM timeslots
                WHERE court_id = :court_id
                ORDER BY start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['court_id' => $courtId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $timeslots = [];
        foreach ($rows as $row) {
            $timeslots[] = new TimeslotModel(
                (int)$row['id'],
                (int)$row['court_id'],
                $row['start_time'],
                $row['end_time']
            );
        }

        return $timeslots;
    }

    public function create(int $courtId, string $startTime, string $endTime): void
 {
    $pdo = $this->getConnection();

    $sql = "INSERT INTO timeslots (court_id, start_time, end_time)
            VALUES (:court_id, :start_time, :end_time)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'court_id' => $courtId,
        'start_time' => $startTime,
        'end_time' => $endTime
    ]);
  }

public function delete(int $id): void
{
    $pdo = $this->getConnection();

    $sql = "DELETE FROM timeslots WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}



}