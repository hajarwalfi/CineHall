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

    public function getSeancesByFilm($filmId)
    {
        return $this->seanceRepository->getByFilmId($filmId);
    }

    public function getSeanceById($id)
    {
        return $this->seanceRepository->findById($id);
    }

}
