<?php

namespace App\Models;

class TimeslotModel
{
    public int $id;
    public int $courtId;
    public string $startTime;
    public string $endTime;

    public function __construct(
        int $id,
        int $courtId,
        string $startTime,
        string $endTime
    ) {
        $this->id = $id;
        $this->courtId = $courtId;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}