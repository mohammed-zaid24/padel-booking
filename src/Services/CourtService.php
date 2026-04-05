<?php

namespace App\Services;

use App\Repositories\CourtRepository;

class CourtService implements ICourtService
{
    private CourtRepository $courtRepository;

    public function __construct()
    {
        $this->courtRepository = new CourtRepository();
    }

    public function getAll(): array
    {
        return $this->courtRepository->getAll();
    }

    public function getById(int $id)
   {
      return $this->courtRepository->getById($id);
   }
    
    public function create(string $name, string $location): int
{
    return $this->courtRepository->create($name, $location);
}

    public function update(int $id, string $name, string $location): void
    {
        $this->courtRepository->update($id, $name, $location);
    }

   public function delete(int $id): void
   {
    $this->courtRepository->delete($id);
   }
}