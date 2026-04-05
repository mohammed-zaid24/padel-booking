<?php

namespace App\Repositories;

use App\Models\TimeslotModel;
use PDO;

class TimeslotRepository extends Repository implements ITimeslotRepository
{
    public function getByCourtId(int $courtId): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT id, court_id, slot_date, start_time, end_time
                FROM timeslots
                WHERE court_id = :court_id
                ORDER BY slot_date ASC, start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['court_id' => $courtId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $timeslots = [];
        foreach ($rows as $row) {
            $timeslots[] = new TimeslotModel(
                (int)$row['id'],
                (int)$row['court_id'],
                $row['slot_date'],
                $row['start_time'],
                $row['end_time']
            );
        }

        return $timeslots;
    }

    public function getByCourtIdAndDate(int $courtId, string $slotDate): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT id, court_id, slot_date, start_time, end_time
                FROM timeslots
                WHERE court_id = :court_id AND slot_date = :slot_date
                ORDER BY start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'court_id' => $courtId,
            'slot_date' => $slotDate,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $timeslots = [];
        foreach ($rows as $row) {
            $timeslots[] = new TimeslotModel(
                (int)$row['id'],
                (int)$row['court_id'],
                $row['slot_date'],
                $row['start_time'],
                $row['end_time']
            );
        }

        return $timeslots;
    }

    public function getById(int $id): ?TimeslotModel
    {
        $pdo = $this->getConnection();

        $sql = "SELECT id, court_id, slot_date, start_time, end_time
                FROM timeslots
                WHERE id = :id
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new TimeslotModel(
            (int)$row['id'],
            (int)$row['court_id'],
            $row['slot_date'],
            $row['start_time'],
            $row['end_time']
        );
    }

    public function create(int $courtId, string $slotDate, string $startTime, string $endTime): void
 {
    $pdo = $this->getConnection();

    $sql = "INSERT INTO timeslots (court_id, slot_date, start_time, end_time)
            VALUES (:court_id, :slot_date, :start_time, :end_time)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'court_id' => $courtId,
        'slot_date' => $slotDate,
        'start_time' => $startTime,
        'end_time' => $endTime
    ]);
  }

public function update(int $id, string $slotDate, string $startTime, string $endTime): bool
{
    $pdo = $this->getConnection();

    $sql = "UPDATE timeslots
            SET slot_date = :slot_date, start_time = :start_time, end_time = :end_time
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'slot_date' => $slotDate,
        'start_time' => $startTime,
        'end_time' => $endTime,
    ]);

    return $stmt->rowCount() > 0;
}

public function delete(int $id): void
{
    $pdo = $this->getConnection();

    $sql = "DELETE FROM timeslots WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}



}