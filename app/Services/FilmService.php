<?php

namespace App\Services;

use App\Repositories\Interfaces\FilmRepositoryInterface;

class FilmService
{
    protected $filmRepository;

    public function __construct(FilmRepositoryInterface $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }

    public function getAllFilms()
    {
        return $this->filmRepository->getAll();
    }

    public function getFilmById($id)
    {
        return $this->filmRepository->getById($id);
    }

    public function createFilm($data)
    {
        return $this->filmRepository->create($data);
    }

    public function updateFilm($id, $data)
    {
        return $this->filmRepository->update($id, $data);
    }

    public function deleteFilm($id)
    {
        return $this->filmRepository->delete($id);
    }
}
