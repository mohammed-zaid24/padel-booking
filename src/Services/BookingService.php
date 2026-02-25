<?php

namespace App\Services;

use App\Repositories\BookingRepository;

class BookingService implements IBookingService
{
    private BookingRepository $bookingRepository;

    public function __construct()
    {
        $this->bookingRepository = new BookingRepository();
    }

    public function getBookedTimeslotIds(int $courtId, string $date): array
    {
        return $this->bookingRepository->getBookedTimeslotIds($courtId, $date);
    }

    public function createBooking(int $userId, int $courtId, string $date, int $timeslotId): void
    {
    $this->bookingRepository->createBooking($userId, $courtId, $date, $timeslotId);
    }
    
    public function getByUserId(int $userId): array
    {
    return $this->bookingRepository->getByUserId($userId);
    }

    public function cancelBooking(int $bookingId, int $userId): bool
    {
        return $this->bookingRepository->deleteByIdAndUserId($bookingId, $userId);
    }
    
    public function getAll(): array
{
    return $this->bookingRepository->getAll();
}

   public function deleteById(int $bookingId): void
   {
    $this->bookingRepository->deleteById($bookingId);
   }

}