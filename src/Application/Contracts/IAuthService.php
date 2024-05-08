<?php

namespace App\Application\Contracts;

use App\Application\Models\Auth\AuthResult;
use App\Application\Models\Auth\RegisterUserDto;

interface IAuthService {
    public function requestLogin(string $username);

    public function registerUser(RegisterUserDto  $registerUserDto);

    public function verifyLogin(string $code) : AuthResult;
}