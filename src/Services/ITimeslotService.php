<?php

namespace App\Services;

interface ITimeslotService
{
    public function getByCourtId(int $courtId): array;
    public function getByCourtIdAndDate(int $courtId, string $slotDate): array;
    public function getById(int $id): ?\App\Models\TimeslotModel;
    public function create(int $courtId, string $slotDate, string $startTime, string $endTime): void;
    public function update(int $id, string $slotDate, string $startTime, string $endTime): bool;
    public function delete(int $id): void;
}