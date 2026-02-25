<?php

namespace App\Repositories;

use App\Models\CourtModel;

interface ICourtRepository
{
    public function getAll(): array; // array of CourtModel
    public function getById(int $id): ?\App\Models\CourtModel;
    public function create(string $name, string $location): void;
    public function delete(int $id): void;
}