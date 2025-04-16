<?php
namespace App\Repositories\Interfaces;

interface SiegeRepositoryInterface {
    public function getAll();
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getBySalleId(int $salleId);
    public function createCoupleSeats(int $salleId, string $rangee, int $numeroDebut);
}
