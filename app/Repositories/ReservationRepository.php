<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Models\Reservation;
use App\Models\Siege;
use Illuminate\Database\Eloquent\Collection;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function creerReservation(array $data): Reservation
    {
        return Reservation::create($data);
    }


    public function getSiegesDisponibles(int $seanceId): Collection
    {
        return Siege::where('seance_id', $seanceId)
            ->where('statut', 'disponible')
            ->get();
    }

    public function reserverSieges(array $siegeIds)
    {
        // Mettre à jour le statut des sièges en 'réservé'
        Siege::whereIn('id', $siegeIds)->update(['statut' => 'réservé']);
    }

}
