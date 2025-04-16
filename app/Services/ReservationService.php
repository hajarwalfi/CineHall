<?php
namespace App\Services;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\SiegeRepositoryInterface;

class ReservationService
{
    protected $reservationRepository;
    protected $siegeRepository;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        SiegeRepositoryInterface $siegeRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->siegeRepository = $siegeRepository;
    }

    public function getAllReservations()
    {
        return $this->reservationRepository->getAll();
    }

    public function getReservationById($id)
    {
        return $this->reservationRepository->getById($id);
    }

    public function getReservationByCode($code)
    {
        return $this->reservationRepository->getByCode($code);
    }

    public function createReservation($data)
    {
        return $this->reservationRepository->create($data);
    }

    public function updateReservation($id, $data)
    {
        return $this->reservationRepository->update($id, $data);
    }

    public function deleteReservation($id)
    {
        return $this->reservationRepository->delete($id);
    }

    public function getReservationsBySeance($seanceId)
    {
        return $this->reservationRepository->getBySeanceId($seanceId);
    }

    public function getSiegesDisponiblesBySeance($seanceId, $salleId)
    {
        // Récupérer tous les sièges de la salle
        $sieges = $this->siegeRepository->getBySalleId($salleId);

        // Récupérer les IDs des sièges déjà réservés
        $siegesReserves = $this->reservationRepository->getSiegesReservesBySeance($seanceId);

        // Filtrer les sièges disponibles
        $siegesDisponibles = $sieges->filter(function ($siege) use ($siegesReserves) {
            return !in_array($siege->id, $siegesReserves);
        });

        return $siegesDisponibles;
    }

    public function reserverSiege($seanceId, $siegeId, $userId = null)
    {
        return $this->reservationRepository->reserverSiege($seanceId, $siegeId, $userId);
    }

    public function annulerReservation($reservationId)
    {
        return $this->reservationRepository->annulerReservation($reservationId);
    }

    public function payerReservation($reservationId)
    {
        return $this->reservationRepository->payerReservation($reservationId);
    }

    // Obtenir la carte des sièges pour une séance
    public function getCarteSieges($seanceId, $salleId)
    {
        // Récupérer tous les sièges de la salle
        $sieges = $this->siegeRepository->getBySalleId($salleId);

        // Récupérer les IDs des sièges déjà réservés
        $siegesReserves = $this->reservationRepository->getSiegesReservesBySeance($seanceId);

        // Organiser les sièges par rangée
        $siegesParRangee = [];
        foreach ($sieges as $siege) {
            if (!isset($siegesParRangee[$siege->rangee])) {
                $siegesParRangee[$siege->rangee] = [];
            }

            // Ajouter des informations sur la disponibilité
            $siege->disponible = !in_array($siege->id, $siegesReserves);

            $siegesParRangee[$siege->rangee][$siege->numero] = $siege;
        }

        // Trier les rangées
        ksort($siegesParRangee);

        return $siegesParRangee;
    }

    // Annuler les réservations expirées
    public function annulerReservationsExpirees()
    {
        return $this->reservationRepository->annulerReservationsExpirees();
    }
}
