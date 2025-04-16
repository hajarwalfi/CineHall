<?php
// app/Repositories/ReservationRepository.php
namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Siege;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getAll()
    {
        return Reservation::all();
    }

    public function getById(int $id)
    {
        return Reservation::findOrFail($id);
    }

    public function getByCode(string $code)
    {
        return Reservation::where('code', $code)->first();
    }

    public function create(array $data)
    {
        return Reservation::create($data);
    }

    public function update(int $id, array $data)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($data);
        return $reservation;
    }

    public function delete(int $id)
    {
        $reservation = Reservation::findOrFail($id);
        return $reservation->delete();
    }

    public function getBySeanceId(int $seanceId)
    {
        return Reservation::where('seance_id', $seanceId)->get();
    }

    public function getSiegesReservesBySeance(int $seanceId)
    {
        return Reservation::where('seance_id', $seanceId)
            ->where('statut', '!=', 'Annulé')
            ->pluck('siege_id')
            ->toArray();
    }

    public function reserverSiege(int $seanceId, int $siegeId, int $userId = null)
    {
        // Vérifier si le siège est déjà réservé
        $existingReservation = Reservation::where('seance_id', $seanceId)
            ->where('siege_id', $siegeId)
            ->where('statut', '!=', 'Annulé')
            ->first();

        if ($existingReservation) {
            throw new \Exception('Ce siège est déjà réservé.');
        }

        // Vérifier si c'est un siège de couple
        $siege = Siege::findOrFail($siegeId);

        // Définir la date d'expiration (15 minutes)
        $expireAt = Carbon::now()->addMinutes(15);

        // Générer un code de réservation unique
        $code = Reservation::generateUniqueCode();

        if ($siege->type === 'Couple') {
            // Si c'est un siège de couple, on doit réserver les deux sièges
            return DB::transaction(function () use ($seanceId, $siege, $userId, $expireAt, $code) {
                // Réserver le siège actuel
                $reservation1 = Reservation::create([
                    'seance_id' => $seanceId,
                    'siege_id' => $siege->id,
                    'user_id' => $userId,
                    'statut' => 'Réservé',
                    'code' => $code,
                    'expire_at' => $expireAt
                ]);

                // Réserver le siège associé
                $reservation2 = Reservation::create([
                    'seance_id' => $seanceId,
                    'siege_id' => $siege->est_couple_avec,
                    'user_id' => $userId,
                    'statut' => 'Réservé',
                    'code' => $code . '-2', // Code lié mais unique
                    'expire_at' => $expireAt
                ]);

                return [$reservation1, $reservation2];
            });
        } else {
            // Siège standard
            return Reservation::create([
                'seance_id' => $seanceId,
                'siege_id' => $siegeId,
                'user_id' => $userId,
                'statut' => 'Réservé',
                'code' => $code,
                'expire_at' => $expireAt
            ]);
        }
    }

    public function annulerReservation(int $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // Vérifier si la réservation est déjà payée
        if ($reservation->statut === 'Payé') {
            throw new \Exception('Impossible d\'annuler une réservation déjà payée.');
        }

        return DB::transaction(function () use ($reservation) {
            $reservation->statut = 'Annulé';
            $reservation->save();

            // Si c'est un siège de couple, annuler aussi l'autre réservation
            $siege = $reservation->siege;
            if ($siege->type === 'Couple' && $siege->est_couple_avec) {
                $autreReservation = Reservation::where('seance_id', $reservation->seance_id)
                    ->where('siege_id', $siege->est_couple_avec)
                    ->where('statut', '!=', 'Annulé')
                    ->first();

                if ($autreReservation) {
                    $autreReservation->statut = 'Annulé';
                    $autreReservation->save();
                }
            }

            return $reservation;
        });
    }

    public function payerReservation(int $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // Vérifier si la réservation est déjà annulée
        if ($reservation->statut === 'Annulé') {
            throw new \Exception('Impossible de payer une réservation annulée.');
        }

        // Vérifier si la réservation est expirée
        if ($reservation->isExpired()) {
            throw new \Exception('Cette réservation a expiré.');
        }

        return DB::transaction(function () use ($reservation) {
            $reservation->statut = 'Payé';
            $reservation->expire_at = null; // Supprimer la date d'expiration
            $reservation->save();

            // Si c'est un siège de couple, marquer aussi l'autre réservation comme payée
            $siege = $reservation->siege;
            if ($siege->type === 'Couple' && $siege->est_couple_avec) {
                $autreReservation = Reservation::where('seance_id', $reservation->seance_id)
                    ->where('siege_id', $siege->est_couple_avec)
                    ->where('statut', '!=', 'Annulé')
                    ->first();

                if ($autreReservation) {
                    $autreReservation->statut = 'Payé';
                    $autreReservation->expire_at = null;
                    $autreReservation->save();
                }
            }

            return $reservation;
        });
    }

    public function getExpiredReservations()
    {
        return Reservation::where('statut', 'Réservé')
            ->where('expire_at', '<', Carbon::now())
            ->get();
    }

    public function annulerReservationsExpirees()
    {
        $expiredReservations = $this->getExpiredReservations();
        $count = 0;

        foreach ($expiredReservations as $reservation) {
            try {
                $this->update($reservation->id, [
                    'statut' => 'Annulé'
                ]);
                $count++;
            } catch (\Exception $e) {
                // Continuer avec les autres réservations en cas d'erreur
                continue;
            }
        }

        return $count;
    }
}
