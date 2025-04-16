<?php
// app/Http/Controllers/ReservationController.php
namespace App\Http\Controllers;

use App\Services\ReservationService;
use App\Services\SeanceService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $reservationService;
    protected $seanceService;

    public function __construct(ReservationService $reservationService, SeanceService $seanceService)
    {
        $this->reservationService = $reservationService;
        $this->seanceService = $seanceService;
    }

    public function index()
    {
        $reservations = $this->reservationService->getAllReservations();
        return response()->json($reservations);
    }

    public function show($id)
    {
        $reservation = $this->reservationService->getReservationById($id);
        return response()->json($reservation);
    }

    public function getByCode($code)
    {
        $reservation = $this->reservationService->getReservationByCode($code);

        if (!$reservation) {
            return response()->json(['error' => 'Réservation non trouvée'], 404);
        }

        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'seance_id' => 'required|exists:seances,id',
            'siege_id' => 'required|exists:sieges,id',
            'user_id' => 'nullable|exists:users,id'
        ]);

        try {
            $reservation = $this->reservationService->reserverSiege(
                $validated['seance_id'],
                $validated['siege_id'],
                $validated['user_id'] ?? null
            );

            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'statut' => 'in:Réservé,Payé,Annulé'
        ]);

        try {
            $reservation = $this->reservationService->updateReservation($id, $validated);
            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->reservationService->annulerReservation($id);
            return response()->json(['message' => 'Réservation annulée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function payer($id)
    {
        try {
            $reservation = $this->reservationService->payerReservation($id);
            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getBySeance($seanceId)
    {
        $reservations = $this->reservationService->getReservationsBySeance($seanceId);
        return response()->json($reservations);
    }

    public function getSiegesDisponibles($seanceId)
    {
        // Récupérer la séance pour obtenir l'ID de la salle
        $seance = $this->seanceService->getSeanceById($seanceId);

        if (!$seance) {
            return response()->json(['error' => 'Séance non trouvée'], 404);
        }

        $salleId = $seance->salle_id;

        $siegesDisponibles = $this->reservationService->getSiegesDisponiblesBySeance($seanceId, $salleId);
        return response()->json($siegesDisponibles);
    }

    public function getCarteSieges($seanceId)
    {
        // Récupérer la séance pour obtenir l'ID de la salle
        $seance = $this->seanceService->getSeanceById($seanceId);

        if (!$seance) {
            return response()->json(['error' => 'Séance non trouvée'], 404);
        }

        $salleId = $seance->salle_id;

        $carteSieges = $this->reservationService->getCarteSieges($seanceId, $salleId);
        return response()->json($carteSieges);
    }

    public function annulerExpirees()
    {
        $count = $this->reservationService->annulerReservationsExpirees();
        return response()->json(['message' => $count . ' réservation(s) expirée(s) annulée(s)']);
    }
}
