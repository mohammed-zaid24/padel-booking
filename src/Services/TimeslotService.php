<?php

namespace App\Services;

use App\Repositories\TimeslotRepository;

class TimeslotService implements ITimeslotService
{
    private TimeslotRepository $timeslotRepository;

    public function __construct()
    {
        $this->timeslotRepository = new TimeslotRepository();
    }

    public function getByCourtId(int $courtId): array
    {
        return $this->timeslotRepository->getByCourtId($courtId);
    }

    public function getByCourtIdAndDate(int $courtId, string $slotDate): array
    {
        return $this->timeslotRepository->getByCourtIdAndDate($courtId, $slotDate);
    }

    public function getById(int $id): ?\App\Models\TimeslotModel
    {
        return $this->timeslotRepository->getById($id);
    }

    public function create(int $courtId, string $slotDate, string $startTime, string $endTime): void
  {
    $this->timeslotRepository->create($courtId, $slotDate, $startTime, $endTime);
  }

  public function update(int $id, string $slotDate, string $startTime, string $endTime): bool
  {
    return $this->timeslotRepository->update($id, $slotDate, $startTime, $endTime);
  }

  public function delete(int $id): void
 {
    $this->timeslotRepository->delete($id);
 }

}