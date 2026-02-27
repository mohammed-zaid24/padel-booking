<?php

namespace App\Repositories;

interface IBookingRepository
{
    public function getBookedTimeslotIds(int $courtId, string $date): array;
    public function createBooking(int $userId, int $courtId, string $date, int $timeslotId): void;
    public function getByUserId(int $userId): array;
    /** @return array|null Row with booking_id, user_id, court_id, date, timeslot_id, court_name, start_time, end_time */
    public function getByIdAndUserId(int $bookingId, int $userId): ?array;
    /** Check if court+date+timeslot is already taken by another booking (exclude one booking id) */
    public function isSlotTaken(int $courtId, string $date, int $timeslotId, ?int $excludeBookingId = null): bool;
    public function updateBooking(int $bookingId, int $userId, string $date, int $timeslotId): bool;
    public function deleteByIdAndUserId(int $bookingId, int $userId): bool;
    public function getAll(): array;
    public function deleteById(int $bookingId): void;
}
