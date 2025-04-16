<?php
namespace App\Repositories\Interfaces;



interface UserRepositoryInterface
{
    public function findById($id);
    public function update($id, array $data);
    public function delete($id);
    public function create(array $data);
}
