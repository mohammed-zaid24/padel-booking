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

    public function getByIdAndUserId(int $bookingId, int $userId): ?array
    {
        $pdo = $this->getConnection();

        $sql = "
            SELECT b.id AS booking_id, b.user_id, b.court_id, b.date, b.timeslot_id,
                   c.name AS court_name, t.start_time, t.end_time
            FROM bookings b
            INNER JOIN courts c ON c.id = b.court_id
            INNER JOIN timeslots t ON t.id = b.timeslot_id
            WHERE b.id = :id AND b.user_id = :user_id
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $bookingId, 'user_id' => $userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function isSlotTaken(int $courtId, string $date, int $timeslotId, ?int $excludeBookingId = null): bool
    {
        $pdo = $this->getConnection();

        $sql = "SELECT 1 FROM bookings
                WHERE court_id = :court_id AND date = :date AND timeslot_id = :timeslot_id";
        $params = [
            'court_id' => $courtId,
            'date' => $date,
            'timeslot_id' => $timeslotId,
        ];
        if ($excludeBookingId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeBookingId;
        }
        $sql .= " LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetch();
    }

    public function isTimeslotForCourtAndDate(int $courtId, string $date, int $timeslotId): bool
    {
        $pdo = $this->getConnection();

        $sql = "SELECT 1
                FROM timeslots
                WHERE id = :id AND court_id = :court_id AND slot_date = :slot_date
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $timeslotId,
            'court_id' => $courtId,
            'slot_date' => $date,
        ]);

        return (bool) $stmt->fetch();
    }

    public function updateBooking(int $bookingId, int $userId, string $date, int $timeslotId): bool
    {
        $pdo = $this->getConnection();

        $sql = "UPDATE bookings SET date = :date, timeslot_id = :timeslot_id
                WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $bookingId,
            'user_id' => $userId,
            'date' => $date,
            'timeslot_id' => $timeslotId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function getById(int $bookingId): ?array
    {
        $pdo = $this->getConnection();

        $sql = "
            SELECT b.id AS booking_id, b.user_id, b.court_id, b.date, b.timeslot_id,
                   c.name AS court_name, t.start_time, t.end_time,
                   u.name AS user_name, u.email AS user_email
            FROM bookings b
            INNER JOIN courts c ON c.id = b.court_id
            INNER JOIN timeslots t ON t.id = b.timeslot_id
            INNER JOIN users u ON u.id = b.user_id
            WHERE b.id = :id
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $bookingId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function updateBookingById(int $bookingId, string $date, int $timeslotId): bool
    {
        $pdo = $this->getConnection();

        $sql = "UPDATE bookings SET date = :date, timeslot_id = :timeslot_id
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $bookingId,
            'date' => $date,
            'timeslot_id' => $timeslotId,
        ]);

        return $stmt->rowCount() > 0;
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