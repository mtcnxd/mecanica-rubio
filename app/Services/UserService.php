<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getAll()
    {
        return User::all();
    }

    public function storeUser($data): User
    {
        return User::create($data);
    }

    public function updateUser(array $data, string $id): User
    { 
        $user = User::findOrFail($id);

        $user->name     = $data['name'];
        $user->status   = $data['status'];
        $user->rol      = $data['rol'];
        $user->comments = $data['comments'];

        $user->save();

        return $user;
    }
}