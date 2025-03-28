<?php

namespace App\Repositories;

use App\Models\Film;
use App\Repositories\Interfaces\FilmRepositoryInterface;

class FilmRepository implements FilmRepositoryInterface
{
    public function getAll()
    {
        return Film::all();
    }

    public function getById(int $id) // Correction ici
    {
        return Film::findOrFail($id);
    }

    public function create(array $data)
    {
        return Film::create($data);
    }

    public function update(int $id, array $data)
    {
        $film = Film::findOrFail($id);
        $film->update($data);
        return $film;
    }

    public function delete(int $id)
    {
        $film = Film::findOrFail($id);
        return $film->delete();
    }
}
