<?php

namespace App\Repositories;

use App\Models\Siege;
use App\Repositories\Interfaces\SiegeRepositoryInterface;

class SiegeRepository implements SiegeRepositoryInterface
{
    public function all()
    {
        return Siege::all();
    }

    public function create(array $data)
    {
        return Siege::create($data);
    }

    public function find($id)
    {
        return Siege::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $siege = $this->find($id);
        $siege->update($data);
        return $siege;
    }


    public function delete($id)
    {
        $siege = $this->find($id);
        $siege->delete();
        return true;
    }
    public function getSiegesDisponibles($seanceId)
    {
        return Siege::where('statut', 'disponible')
            ->whereHas('seance', function ($query) use ($seanceId) {
                $query->where('id', $seanceId);
            })
            ->get();
    }
    public function reserverSiege($id)
    {
        $siege = $this->find($id);

        if ($siege->statut === 'réservé') {
            throw new \Exception("Ce siège est déjà réservé.");
        }

        $siege->update(['statut' => 'réservé']);
        return $siege;
    }

    public function estDisponible($id)
    {
        $siege = $this->find($id);
        return $siege->statut === 'disponible';
    }


}
