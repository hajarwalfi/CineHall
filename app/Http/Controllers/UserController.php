<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:user,admin',
        ]);

        $user = $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user',
        ]);

        return response()->json(['message' => 'User created successfully!', 'user' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        if ($id != auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = $this->userRepository->findById($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|in:user,admin',
        ]);

        $updatedUser = $this->userRepository->update($user, $request->only(['name', 'email', 'password', 'role']));

        return response()->json(['message' => 'User updated successfully', 'user' => $updatedUser]);
    }

    public function delete($id)
    {

        $authUser = auth()->user();
        if (!$authUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = User::where('id', $id)->exists();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($id != $authUser->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->userRepository->delete($id);

        return response()->json(['message' => 'User deleted successfully']);

    }

}
