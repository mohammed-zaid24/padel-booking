<?php

namespace App\Repositories;

use App\Models\TimeslotModel;

interface ITimeslotRepository
{
    public function getByCourtId(int $courtId): array; // array of TimeslotModel
    public function create(int $courtId, string $startTime, string $endTime): void;
    public function delete(int $id): void;
}