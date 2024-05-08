<?php

namespace App\Application\Models\Auth;

readonly class AuthResult
{
    public bool $success;
    public ?AuthUserDto $user;
    public ?string $errorMessage;
}