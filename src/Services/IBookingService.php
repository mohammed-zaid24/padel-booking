<?php

namespace App\Services;

interface IBookingService
{
    public function getBookedTimeslotIds(int $courtId, string $date): array;
    public function createBooking(int $userId, int $courtId, string $date, int $timeslotId): void;
    public function getByUserId(int $userId): array;
    public function cancelBooking(int $bookingId, int $userId): bool;
    public function getAll(): array;
    public function deleteById(int $bookingId): void;
}

