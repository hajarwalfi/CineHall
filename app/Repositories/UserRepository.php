<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    // Fetch user by ID
    public function findById($id)
    {
        return $this->model->find($id);
    }

    // Update user
    public function update($id, array $data)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->update($data);
        }
        return $user;
    }

    // Delete user
    public function delete($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->delete();
        }
        return $user;
    }

    // Create user
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
