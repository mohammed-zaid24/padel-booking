<?php

namespace App\Repositories;

use App\Models\TimeslotModel;

interface ITimeslotRepository
{
    public function getByCourtId(int $courtId): array; // array of TimeslotModel
    public function getByCourtIdAndDate(int $courtId, string $slotDate): array; // array of TimeslotModel
    public function getById(int $id): ?TimeslotModel;
    public function create(int $courtId, string $slotDate, string $startTime, string $endTime): void;
    public function update(int $id, string $slotDate, string $startTime, string $endTime): bool;
    public function delete(int $id): void;
}