<?php

namespace App\Services;

use App\Models\Seance;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Services\SiegeService;
use Illuminate\Support\Facades\DB;
use Exception;

class ReservationService
{
    protected $reservationRepository;
    protected $siegeService;

    public function __construct(ReservationRepositoryInterface $reservationRepository, SiegeService $siegeService)
    {
        $this->reservationRepository = $reservationRepository;
        $this->siegeService = $siegeService;
    }

    public function reserver(int $seanceId, array $siegeIds)
    {
        // Vérifier si la séance existe
        $seance = Seance::findOrFail($seanceId);

        // Vérifier la disponibilité des sièges et réserver les sièges via SiegeService
        $siegesReserves = [];
        foreach ($siegeIds as $siegeId) {
            try {
                // Appel à SiegeService pour réserver les sièges
                $siegesReserves[] = $this->siegeService->reserverSiege($siegeId, $seanceId);
            } catch (Exception $e) {
                throw new Exception("Erreur lors de la réservation du siège $siegeId: " . $e->getMessage());
            }
        }

        // Démarrer une transaction pour créer la réservation
        DB::beginTransaction();
        try {
            // Créer la réservation
            $reservation = $this->reservationRepository->creerReservation([
                'seance_id' => $seanceId,
                'user_id' => auth()->id(),
                'statut' => 'pending',
                'expiration_at' => now()->addMinutes(15),
            ]);

            // Commit de la transaction
            DB::commit();

            // Retourner les informations de la réservation
            return [
                'reservation' => $reservation,
                'sieges_reserves' => $siegesReserves,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erreur lors de la création de la réservation : " . $e->getMessage());
        }
    }
}
