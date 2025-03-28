<?php

namespace App\Repositories\Interfaces;

interface SiegeRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function find($id);

    public function update($id, array $data);

    public function delete($id);
    public function getSiegesDisponibles($seanceId);
    public function reserverSiege($id);
    public function estDisponible($id);
}
