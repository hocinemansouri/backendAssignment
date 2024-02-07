<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll();

    public function getById($userId): ?User;

    public function getByEmail($userEmail): ?User;

    public function create(array $data): User;

    public function update(User $userId, array $data): User;

    public function changePassword($password);
    
    public function delete(User $userId): void;
}
