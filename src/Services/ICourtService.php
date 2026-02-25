<?php

namespace App\Services;

interface ICourtService
{
    public function getAll(): array;
    public function getById(int $id);
    public function create(string $name, string $location): void;
    public function delete(int $id): void;
}