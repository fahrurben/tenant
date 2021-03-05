<?php
namespace Kyrosoft\Tenant\Repositories;

use Kyrosoft\Tenant\Models\User;

class UserRepository
{
    public function findById(int $id): ?User
    {
        return User::where('id', $id)
            ->whereNull('deleted_at')
            ->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)
            ->whereNull('deleted_at')
            ->first();
    }

    public function findByToken(int $id, String $token): ?user
    {
        return User::where('id', $id)
            ->where('remember_token', $token)
            ->whereNull('deleted_at')
            ->first();
    }

    public function updateRememberMeToken(int $id, string $token): void
    {
        $user = $this->findById($id);

        if ($user === null) throw new \Exception('Entity not exist');

        $user->remember_token = $token;
        $user->save();
    }
}