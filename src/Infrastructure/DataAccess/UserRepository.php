<?php

namespace App\Infrastructure\DataAccess;

use App\Application\Abstractions\IUserRepository;
use App\Application\Models\Auth\RegisterUserDto;
use App\Application\Models\Users\User;
use App\Application\Models\Users\UserDto;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;

class UserRepository implements IUserRepository
{
    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
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

    /**
     * @throws Exception
     */
    public function addUser(RegisterUserDto $userDto): User
    {
        try {
            // Start transaction
            $this->connection->beginTransaction();

            $this->logger->info(var_export($userDto, true));

            // Prepare the INSERT statement
            $stmt = $this->connection->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
            $stmt->bindValue(1, $userDto->username);
            $stmt->bindValue(2, $userDto->email);

            // Execute the INSERT statement
            $stmt->executeStatement();

            // Get the ID of the newly inserted user
            $userId = $this->connection->lastInsertId();

            // Commit transaction
            $this->connection->commit();

            return $this->getUserByUsername($userDto->username);
        } catch (Exception $e) {
            // Rollback transaction in case of exception
            $this->connection->rollBack();
            throw $e;
        }
    }

}