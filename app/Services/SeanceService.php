<?php

namespace App\Services;

use App\Repositories\Interfaces\SeanceRepositoryInterface;

class SeanceService
{
    protected $seanceRepository;

    public function __construct(SeanceRepositoryInterface $seanceRepository)
    {
        $this->seanceRepository = $seanceRepository;
    }

    public function getAllSeances()
    {
        return $this->seanceRepository->getAll();
    }

    public function createSeance($data)
    {
        return $this->seanceRepository->create($data);
    }

    public function updateSeance($id, $data)
    {
        return $this->seanceRepository->update($id, $data);
    }

    public function deleteSeance($id)
    {
        return $this->seanceRepository->delete($id);
    }

    public function getSeancesByType($type)
    {
        return $this->seanceRepository->getByType($type);
    }

    public function getSeanceById($id)
    {
        return $this->seanceRepository->findById($id);
    }

}
