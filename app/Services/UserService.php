<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Arr;
class UserService
{
    public function getAllUsers(Request $request)
    {
        $email = $request->query('email');
        $itemsPerPage = $request->query('items_per_page', 15);

        return User::filterByEmail($email)
            ->orderBy('email', 'DESC')
            ->paginate($itemsPerPage);
    }

    public function createUser(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        // Manually assign the password
        if (isset($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        return $user;
    }

    public function getUser(User $user)
    {
        $currentUser = auth()->user();
        if ($currentUser->role !== 'admin' && $currentUser->id !== $user->id) {
            throw new Exception('Unauthorized: You do not have permission to delete this user.', 403);
        }

        return $user;
    }

    public function updateUser(User $user, array $data)
    {
        $currentUser = auth()->user();
        if ($currentUser->role !== 'admin' && $currentUser->id !== $user->id) {
            throw new Exception('Unauthorized: You do not have permission to update this user.', 403);
        }

        $user->update(Arr::except($data, ['password', 'role']));
        if (isset($data['password']) || isset($data['role'])) {
            $user->password = $data['password'];
            $user->role = $data['role'];
            $user->save();
        }

        return $user;
    }

    public function deleteUser(User $user)
    {
        $currentUser = auth()->user();
        if ($currentUser->role !== 'admin' && $currentUser->id !== $user->id) {
            throw new Exception('Unauthorized: You do not have permission to delete this user.', 403);
        }

        $user->delete();
    }
}
