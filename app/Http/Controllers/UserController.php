<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Show user details
    public function show(Request $request)
    {
         $user = $this->userService->getUserDetails(auth()->id());

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    // Update user details
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8',
        ]);

        $updatedUser = $this->userService->updateUser(auth()->id(), $validatedData);

        if (!$updatedUser) {
            return response()->json(['error' => 'User not found or update failed'], 404);
        }

        return response()->json(['message' => 'User updated successfully', 'user' => $updatedUser], 200);
    }

    // Delete user account
    public function destroy()
    {
        $deletedUser = $this->userService->deleteUser(auth()->id());

        if (!$deletedUser) {
            return response()->json(['error' => 'User not found or deletion failed'], 404);
        }

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
