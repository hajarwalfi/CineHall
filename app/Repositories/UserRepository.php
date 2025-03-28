<?php


namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);
        return $user->delete();
    }

    public function getAll()
    {
        return User::all();
    }

    public function findById(int $id)
    {
        return User::find($id);
    }
}
