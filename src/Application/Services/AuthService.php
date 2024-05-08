<?php

namespace App\Application\Services;

use App\Application\Abstractions\IMailgunIntegration;
use App\Application\Abstractions\IUserRepository;
use App\Application\Contracts\IAuthService;
use App\Application\Models\Auth\AuthResult;
use App\Application\Models\Auth\RegisterUserDto;

class AuthService implements IAuthService
{

    private readonly IUserRepository $userRepository;
    private readonly IMailgunIntegration $mailgunIntegration;

    public function __construct(IUserRepository $userRepository, IMailgunIntegration $mailgunIntegration)
    {
        $this->userRepository = $userRepository;
        $this->mailgunIntegration = $mailgunIntegration;
    }

    public function requestLogin(string $username): void
    {
        $user = $this->userRepository->getUserByUsername($username);
        $this->mailgunIntegration->sendMail($user->email, 'New Login', 'jwt-token');
    }

    public function registerUser(RegisterUserDto $registerUserDto): void
    {
        $this->userRepository->addUser($registerUserDto);
    }

    public function verifyLogin(string $code): AuthResult
    {
        return new AuthResult();
    }
}