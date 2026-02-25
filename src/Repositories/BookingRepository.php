<?php

namespace App\Repositories;

use PDO;

class BookingRepository extends Repository implements IBookingRepository
{
    public function getBookedTimeslotIds(int $courtId, string $date): array
    {
        $pdo = $this->getConnection();

        $sql = "SELECT timeslot_id
                FROM bookings
                WHERE court_id = :court_id AND date = :date";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'court_id' => $courtId,
            'date' => $date
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = (int)$row['timeslot_id'];
        }

        return $ids;
    }

    public function createBooking(int $userId, int $courtId, string $date, int $timeslotId): void
 {
    $pdo = $this->getConnection();

    $sql = "INSERT INTO bookings (user_id, court_id, date, timeslot_id)
            VALUES (:user_id, :court_id, :date, :timeslot_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'court_id' => $courtId,
        'date' => $date,
        'timeslot_id' => $timeslotId
    ]);
  }

  public function getByUserId(int $userId): array
 {
    $pdo = $this->getConnection();

    $sql = "
        SELECT
            b.id AS booking_id,
            b.date,
            c.name AS court_name,
            c.location AS court_location,
            t.start_time,
            t.end_time
        FROM bookings b
        INNER JOIN courts c ON c.id = b.court_id
        INNER JOIN timeslots t ON t.id = b.timeslot_id
        WHERE b.user_id = :user_id
        ORDER BY b.date ASC, t.start_time ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $userId]);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

//   public function deleteByIdAndUserId(int $bookingId, int $userId): bool
//   {
//     $pdo = $this->getConnection();

//     $sql = "DELETE FROM bookings WHERE id = :booking_id AND user_id = :user_id";

//     $stmt = $pdo->prepare($sql);
//     return $stmt->execute([
//       'booking_id' => $bookingId,
//       'user_id' => $userId
//     ]);
//   }
  
  public function deleteByIdAndUserId(int $bookingId, int $userId): bool
   {
    $pdo = $this->getConnection();

    $sql = "DELETE FROM bookings WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $bookingId,
        'user_id' => $userId
    ]);

    return $stmt->rowCount() > 0;
  }

  public function getAll(): array
  {
      $pdo = $this->getConnection();

      $sql = "
          SELECT
              b.id AS booking_id,
              b.date,
              u.name AS user_name,
              u.email AS user_email,
              c.name AS court_name,
              c.location AS court_location,
              t.start_time,
              t.end_time
          FROM bookings b
          INNER JOIN users u ON u.id = b.user_id
          INNER JOIN courts c ON c.id = b.court_id
          INNER JOIN timeslots t ON t.id = b.timeslot_id
          ORDER BY b.date ASC, t.start_time ASC
      ";

      $stmt = $pdo->query($sql);
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function deleteById(int $bookingId): void
  {
      $pdo = $this->getConnection();

      $sql = "DELETE FROM bookings WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['id' => $bookingId]);
  }

}