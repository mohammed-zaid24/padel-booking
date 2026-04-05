<?php

namespace App\Models;

class TimeslotModel
{
    public int $id;
    public int $courtId;
    public string $slotDate;
    public string $startTime;
    public string $endTime;

    public function __construct(
        int $id,
        int $courtId,
        string $slotDate,
        string $startTime,
        string $endTime
    ) {
        $this->id = $id;
        $this->courtId = $courtId;
        $this->slotDate = $slotDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}