<?php


namespace App\Repositories\Interfaces;

interface SeanceRepositoryInterface {
    public function getAll();
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByFilmId(int $filmId);
}

