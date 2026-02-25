<?php

namespace App\Services;

interface ITimeslotService
{
    public function getByCourtId(int $courtId): array;
    public function create(int $courtId, string $startTime, string $endTime): void;
    public function delete(int $id): void;
}