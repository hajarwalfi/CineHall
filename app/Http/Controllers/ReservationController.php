<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }
    // Méthode pour vérifier et expirer les réservations
    public function checkAndExpireReservations()
    {
        // Vérifier toutes les réservations qui sont "pending" et dont l'expiration est passée
        $reservations = Reservation::where('statut', 'pending')
            ->where('expiration_at', '<', now())
            ->get();

        foreach ($reservations as $reservation) {
            // Expirer les réservations non confirmées après 15 minutes
            $reservation->update(['statut' => 'expired']);
        }

        return response()->json([
            'message' => 'Les réservations expirées ont été mises à jour.',
        ]);
    }

    // Handle the reservation request
    public function reserver(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'seance_id' => 'required|exists:seances,id',  // The session must exist
            'siegeIds' => 'required|array',  // Array of seat IDs
            'siegeIds.*' => 'exists:sieges,id',  // Each seat must exist
        ]);

        $seanceId = $validated['seance_id'];
        $siegeIds = $validated['siegeIds'];

        try {
            // Call the ReservationService to handle the reservation logic
            $reservation = $this->reservationService->reserver($seanceId, $siegeIds);

            // Return the reservation details as a response
            return response()->json([
                'message' => 'Reservation successful',
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            // Handle any errors that may occur during the reservation process
            return response()->json([
                'message' => 'Reservation failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
