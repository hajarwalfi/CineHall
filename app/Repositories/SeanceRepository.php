<?php

namespace App\Repositories;

use App\Models\Seance;
use App\Repositories\Interfaces\SeanceRepositoryInterface;

class SeanceRepository implements SeanceRepositoryInterface
{
    public function getAll()
    {
        return Seance::all();
    }

    public function getById(int $id)
    {
        return Seance::findOrFail($id);
    }

    public function create(array $data)
    {
        return Seance::create($data);
    }

    public function update(int $id, array $data)
    {
        $seance = Seance::findOrFail($id);
        $seance->update($data);
        return $seance;
    }

    public function delete(int $id)
    {
        $seance = Seance::findOrFail($id);
        return $seance->delete();
    }

    public function getByFilmId(int $filmId)
    {
        return Seance::where('film_id', $filmId)->get();
    }
    public function getByType($type)
    {
        return Seance::where('type', $type)->get();
    }
    public function findById($id)
    {
        return Seance::with(['film', 'salle'])->findOrFail($id);
    }

}
