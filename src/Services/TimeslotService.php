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
    public function create(int $courtId, string $startTime, string $endTime): void
  {
    $this->timeslotRepository->create($courtId, $startTime, $endTime);
  }

  public function delete(int $id): void
 {
    $this->timeslotRepository->delete($id);
 }

}