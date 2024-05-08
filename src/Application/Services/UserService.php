<?php

namespace App\Application\Services;

use App\Application\Contracts\IUserService;
use App\Application\Models\User;

class UserService implements IUserService
{

    public function getAllUsers(): array
    {
        // TODO: Implement getAllUsers() method.
        return [new User(), new User()];
    }

    public function getUserByUsername(string $username): User
    {
        $user = new User();
        $user->username = $username;

        return $user;
    }
}