<?php

namespace App\Services;

interface IBookingService
{
    public function getBookedTimeslotIds(int $courtId, string $date): array;
    public function createBooking(int $userId, int $courtId, string $date, int $timeslotId): void;
    public function getByUserId(int $userId): array;
    /** @return array|null Booking row with booking_id, user_id, court_id, date, timeslot_id, court_name, start_time, end_time */
    public function getBookingById(int $bookingId, int $userId): ?array;
    public function updateBooking(int $bookingId, int $userId, string $date, int $timeslotId): bool;
    public function cancelBooking(int $bookingId, int $userId): bool;
    public function getAll(): array;
    public function deleteById(int $bookingId): void;
}

