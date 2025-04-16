<?php
namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface {
    public function getAll();
    public function getById(int $id);
    public function getByCode(string $code);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getBySeanceId(int $seanceId);
    public function getSiegesReservesBySeance(int $seanceId);
    public function reserverSiege(int $seanceId, int $siegeId, int $userId = null);
    public function annulerReservation(int $reservationId);
    public function payerReservation(int $reservationId);
    public function getExpiredReservations();
    public function annulerReservationsExpirees();
}
