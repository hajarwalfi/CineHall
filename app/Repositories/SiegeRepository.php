<?php
// app/Repositories/SiegeRepository.php
namespace App\Repositories;

use App\Models\Siege;
use App\Repositories\Interfaces\SiegeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SiegeRepository implements SiegeRepositoryInterface
{
    public function getAll()
    {
        return Siege::all();
    }

    public function getById(int $id)
    {
        return Siege::findOrFail($id);
    }

    public function create(array $data)
    {
        return Siege::create($data);
    }

    public function update(int $id, array $data)
    {
        $siege = Siege::findOrFail($id);
        $siege->update($data);
        return $siege;
    }

    public function delete(int $id)
    {
        $siege = Siege::findOrFail($id);
        return $siege->delete();
    }

    public function getBySalleId(int $salleId)
    {
        return Siege::where('salle_id', $salleId)->orderBy('rangee')->orderBy('numero')->get();
    }

    public function createCoupleSeats(int $salleId, string $rangee, int $numeroDebut)
    {
        // Utiliser une transaction pour s'assurer que les deux sièges sont créés ensemble
        return DB::transaction(function () use ($salleId, $rangee, $numeroDebut) {
            // Créer le premier siège
            $siege1 = Siege::create([
                'salle_id' => $salleId,
                'rangee' => $rangee,
                'numero' => $numeroDebut,
                'type' => 'Couple',
                'est_couple_avec' => null // Sera mis à jour après création du deuxième siège
            ]);

            // Créer le deuxième siège
            $siege2 = Siege::create([
                'salle_id' => $salleId,
                'rangee' => $rangee,
                'numero' => $numeroDebut + 1,
                'type' => 'Couple',
                'est_couple_avec' => $siege1->id
            ]);

            // Mettre à jour le premier siège pour référencer le deuxième
            $siege1->est_couple_avec = $siege2->id;
            $siege1->save();

            return [$siege1, $siege2];
        });
    }
}
