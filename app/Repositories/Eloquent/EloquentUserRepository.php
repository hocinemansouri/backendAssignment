<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::all();
    }
    
    public function getById($userId): ?User
    {
        return User::find($userId);
    }

    public function getByEmail($userEmail): ?User
    {
        return User::where('email', $userEmail)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function changePassword($password){
        
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
