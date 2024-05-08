<?php

namespace App\Infrastructure\DataAccess;

use App\Application\Abstractions\IUserRepository;
use App\Application\Models\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class UserRepository implements IUserRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function getAllUsers(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('u.id', 'u.username', 'u.profile_picture', 'u.email')
            ->from('users', 'u');

        $stmt = $qb->executeQuery();
        return $stmt->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getUserByUsername(string $username): User
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('u.id', 'u.username', 'u.profile_picture', 'u.email')
            ->from('users', 'u')
            ->where('u.username = :username')
            ->setParameter('username', $username);

        $stmt = $qb->executeQuery();
        $userData = $stmt->fetchAssociative();

        if (!$userData) {
            throw new \RuntimeException('User not found.');
        }

        $user = new User();
        $user->id = $userData['id'];
        $user->username = $userData['username'];
        $user->profile_picture = $userData['profile_picture'];
        $user->email = $userData['email'];

        return $user;
    }
}