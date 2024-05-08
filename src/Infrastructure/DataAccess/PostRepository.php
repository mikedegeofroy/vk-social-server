<?php

namespace App\Infrastructure\DataAccess;

use App\Application\Abstractions\IPostRepository;
use App\Application\Models\Posts\Post;
use App\Application\Models\Users\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class PostRepository implements IPostRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function getAllPosts(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('p.id AS post_id', 'p.title', 'p.content', 'p.slug', 'u.id AS user_id', 'u.username', 'u.profile_picture', 'u.email')
            ->from('posts', 'p')
            ->innerJoin('p', 'users', 'u', 'p.author_id = u.id');

        $stmt = $qb->executeQuery();
        $results = $stmt->fetchAllAssociative();
        $posts = [];

        foreach ($results as $row) {
            $author = new User();

            $author->id = $row['user_id'];
            $author->username = $row['username'];
            $author->profile_picture = $row['profile_picture'];
            $author->email = $row['email'];

            $post = new Post();
            $post->id = $row['post_id'];
            $post->title = $row['title'];
            $post->content = $row['content'];
            $post->slug = $row['slug'];
            $post->author = $author;

            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * @throws Exception
     */
    public function getPostBySlug(string $slug): Post
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('p.id', 'p.title', 'p.content', 'p.slug', 'u.id AS user_id', 'u.username', 'u.profile_picture', 'u.email')
            ->from('posts', 'p')
            ->innerJoin('p', 'users', 'u', 'p.author_id = u.id')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug);

        $stmt = $qb->executeQuery();
        $postData = $stmt->fetchAssociative();

        if (!$postData) {
            throw new Exception('Post not found');
        }

        // Assuming you have a constructor in your Post model that accepts an array of attributes
        $result = new Post();

        $author = new User();

        $author->email = $postData['email'];
        $author->username = $postData['username'];
        $author->id = $postData['user_id'];
        $author->profile_picture = $postData['profile_picture'];

        $result->id = $postData['id'];
        $result->title = $postData['title'];
        $result->content = $postData['content'];
        $result->slug = $postData['slug'];
        $result->author = $author;

        return $result;
    }
}