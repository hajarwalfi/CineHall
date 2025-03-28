<?php

namespace App\Repositories\Interfaces;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface
{
    public function creerReservation(array $data): Reservation;

    public function getSiegesDisponibles(int $seanceId): Collection;

    public function reserverSieges(array $siegeIds);
}
