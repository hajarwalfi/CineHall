<?php
namespace App\Services;

use App\Repositories\Interfaces\SalleRepositoryInterface;

class SalleService
{
    protected $SalleRepository;

    public function __construct(SalleRepositoryInterface $SalleRepository)
    {
        $this->SalleRepository = $SalleRepository;
    }

    public function getAllSalles()
    {
        return $this->SalleRepository->getAll();
    }

    public function createSalle($data)
    {
        return $this->SalleRepository->create($data);
    }

    public function updateSalle($id, $data)
    {
        return $this->SalleRepository->update($id, $data);
    }

    public function deleteSalle($id)
    {
        return $this->SalleRepository->delete($id);
    }
}
