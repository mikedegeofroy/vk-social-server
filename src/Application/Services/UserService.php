<?php

namespace App\Application\Services;

use App\Application\Abstractions\IMailgunIntegration;
use App\Application\Abstractions\IUserRepository;
use App\Application\Contracts\IUserService;
use App\Application\Models\Users\User;
use App\Application\Models\Users\UserDto;

readonly class UserService implements IUserService
{
    private IUserRepository $userRepository;
    private IMailgunIntegration $mailgunIntegration;

    public function __construct(IUserRepository $userRepository, IMailgunIntegration $mailgunIntegration)
    {
        $this->userRepository = $userRepository;
        $this->mailgunIntegration = $mailgunIntegration;
    }

    public function getAllUsers(): array
    {
        $users = $this->userRepository->getAllUsers();

        return array_map(function ($user) {
            $result = new UserDTO();

            $result->username = $user['username'];
            $result->profile_picture = $user['profile_picture'];

            return $result;
        }, $users);
    }

    public function getUserByUsername(string $username): UserDto
    {
        $user = $this->userRepository->getUserByUsername($username);

        $this->mailgunIntegration->sendMail($user->email, 'test', 'test');

        return $this->mapUserToDTO($user);
    }

    private function mapUserToDTO(User $user): UserDTO
    {
        $result = new UserDTO();

        $result->username = $user->username;
        $result->profile_picture = $user->profile_picture;

        return $result;
    }
}