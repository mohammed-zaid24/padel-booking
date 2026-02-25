<?php

namespace App\Repositories;

interface IBookingRepository
{
    public function getBookedTimeslotIds(int $courtId, string $date): array; // array of int
    public function createBooking(int $userId, int $courtId, string $date, int $timeslotId): void;
    public function getByUserId(int $userId): array;
    public function deleteByIdAndUserId(int $bookingId, int $userId): bool;
    public function getAll(): array;
    public function deleteById(int $bookingId): void;
}
