<?php

namespace App\Models;

class CourtModel
{
    public int $id;
    public string $name;
    public string $location;

    public function __construct(int $id, string $name, string $location)
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
    }
}