<?php
namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Get user details
    public function getUserDetails($id)
    {
        return $this->userRepository->findById($id);
    }

    // Update user details
    public function updateUser($id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }

    // Delete user
    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }

    // Create user
    public function createUser(array $data)
    {
        return $this->userRepository->create($data);
    }
}
