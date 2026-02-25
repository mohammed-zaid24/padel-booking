<?php

namespace App\Repositories;

use App\Models\CourtModel;
use PDO;

class CourtRepository extends Repository implements ICourtRepository
{
    public function getAll(): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT id, name, location FROM courts ORDER BY id ASC";
        $stmt = $pdo->query($sql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $courts = [];
        foreach ($rows as $row) {
            $courts[] = new CourtModel(
                (int)$row['id'],
                $row['name'],
                $row['location']
            );
        }

        return $courts;
    }

    public function getById(int $id): ?CourtModel
 {
    $pdo = $this->getConnection();

    $sql = "SELECT id, name, location FROM courts WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    }

    return new CourtModel(
        (int)$row['id'],
        $row['name'],
        $row['location']
    );
  }
    public function create(string $name, string $location): void
{
    $pdo = $this->getConnection();

    $sql = "INSERT INTO courts (name, location) VALUES (:name, :location)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'location' => $location
    ]);
}

  public function delete(int $id): void
   {
    $pdo = $this->getConnection();

    $sql = "DELETE FROM courts WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
  }


}