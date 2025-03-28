<?php


namespace App\Services;

use App\Models\Siege;
use App\Repositories\Interfaces\SiegeRepositoryInterface;
use Exception;

class SiegeService
{
    protected $siegeRepository;

    public function __construct(SiegeRepositoryInterface $siegeRepository)
    {
        $this->siegeRepository = $siegeRepository;
    }

    public function createSiege(array $data)
    {
        // Ici tu pourrais ajouter des vérifications métier spécifiques, par exemple
        return $this->siegeRepository->create($data);
    }

    public function updateSiege($id, array $data)
    {
        return $this->siegeRepository->update($id, $data);
    }

    public function deleteSiege($id)
    {
        return $this->siegeRepository->delete($id);
    }

    public function allSieges()
    {
        return $this->siegeRepository->all();
    }

    public function findSiege($id)
    {
        return $this->siegeRepository->find($id);
    }

    public function getSiegesDisponibles($seanceId)
    {
        return $this->siegeRepository->getSiegesDisponibles($seanceId);
    }

    public function reserverSiege($id, $seanceId)
    {
        $siege = $this->siegeRepository->find($id);

        // Vérifier si le siège est disponible
        if (!$this->siegeRepository->estDisponible($id)) {
            throw new Exception("Ce siège est déjà réservé.");
        }

        // Vérifier si la séance est VIP et imposer la réservation de 2 sièges
        if ($siege->salle->seances()->where('id', $seanceId)->where('type', 'VIP')->exists()) {
            // Trouver un siège adjacent
            $adjacent = Siege::where('salle_id', $siege->salle_id)
                ->where('statut', 'disponible')
                ->where('id', '!=', $id) // Exclure le siège actuel
                ->first();

            if (!$adjacent) {
                throw new Exception("Les séances VIP nécessitent deux sièges côte à côte, mais il n'y en a pas de disponible.");
            }

            // Réserver les deux sièges
            $this->siegeRepository->reserverSiege($id);
            $this->siegeRepository->reserverSiege($adjacent->id);

            return ["sieges_reserves" => [$id, $adjacent->id]];
        }

        // Si ce n'est pas une séance VIP, réserver normalement
        $this->siegeRepository->reserverSiege($id);
        return ["sieges_reserves" => [$id]];
    }



}
