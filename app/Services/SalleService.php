<?php
namespace App\Services;

use App\Repositories\Interfaces\SalleRepositoryInterface;

class SalleService
{
    protected $salleRepository;

    public function __construct(SalleRepositoryInterface $salleRepository)
    {
        $this->salleRepository = $salleRepository;
    }

    public function getAllSalles()
    {
        return $this->salleRepository->getAll();
    }

    public function getSalleById($id)
    {
        return $this->salleRepository->getById($id);
    }

    public function createSalle($data)
    {
        return $this->salleRepository->create($data);
    }

    public function updateSalle($id, $data)
    {
        return $this->salleRepository->update($id, $data);
    }

    public function deleteSalle($id)
    {
        return $this->salleRepository->delete($id);
    }

    public function getSallesWithVIP()
    {
        return $this->salleRepository->getSallesWithVIP();
    }
}
