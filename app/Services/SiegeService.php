<?php
namespace App\Services;

use App\Repositories\Interfaces\SiegeRepositoryInterface;

class SiegeService
{
    protected $siegeRepository;

    public function __construct(SiegeRepositoryInterface $siegeRepository)
    {
        $this->siegeRepository = $siegeRepository;
    }

    public function getAllSieges()
    {
        return $this->siegeRepository->getAll();
    }

    public function getSiegeById($id)
    {
        return $this->siegeRepository->getById($id);
    }

    public function createSiege($data)
    {
        return $this->siegeRepository->create($data);
    }

    public function updateSiege($id, $data)
    {
        return $this->siegeRepository->update($id, $data);
    }

    public function deleteSiege($id)
    {
        return $this->siegeRepository->delete($id);
    }

    public function getSiegesBySalle($salleId)
    {
        return $this->siegeRepository->getBySalleId($salleId);
    }

    public function createCoupleSeats($salleId, $rangee, $numeroDebut)
    {
        return $this->siegeRepository->createCoupleSeats($salleId, $rangee, $numeroDebut);
    }

    // Générer une configuration de salle standard
    public function genererConfigurationSalle($salleId, $nbRangees, $siegesParRangee, $supporteVIP = false)
    {
        $sieges = [];

        // Lettres pour les rangées (A, B, C, ...)
        $rangees = range('A', chr(ord('A') + $nbRangees - 1));

        foreach ($rangees as $rangee) {
            for ($numero = 1; $numero <= $siegesParRangee; $numero++) {
                // Déterminer le type de siège
                $type = 'Standard';

                // Créer le siège
                $siege = $this->createSiege([
                    'salle_id' => $salleId,
                    'rangee' => $rangee,
                    'numero' => $numero,
                    'type' => $type,
                    'est_couple_avec' => null
                ]);

                $sieges[] = $siege;
            }
        }

        // Si la salle supporte le VIP, ajouter quelques sièges de couple
        if ($supporteVIP) {
            // Ajouter des sièges de couple dans les dernières rangées
            $derniereRangee = end($rangees);
            $avantDerniereRangee = prev($rangees);

            // Ajouter 2 paires de sièges de couple dans la dernière rangée
            $this->createCoupleSeats($salleId, $derniereRangee, 1);
            $this->createCoupleSeats($salleId, $derniereRangee, 3);

            // Ajouter 2 paires de sièges de couple dans l'avant-dernière rangée
            $this->createCoupleSeats($salleId, $avantDerniereRangee, 1);
            $this->createCoupleSeats($salleId, $avantDerniereRangee, 3);
        }

        return $sieges;
    }
}
