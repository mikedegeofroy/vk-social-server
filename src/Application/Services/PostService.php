<?php

namespace App\Application\Services;

use App\Application\Abstractions\IPostRepository;
use App\Application\Contracts\IPostService;
use App\Application\Models\Posts\PostDto;
use App\Application\Models\Users\UserDto;
use Psr\Log\LoggerInterface;


class PostService implements IPostService
{

    private readonly IPostRepository $postRepository;
    private readonly LoggerInterface $logger;

    public function __construct(IPostRepository $postRepository, LoggerInterface $logger)
    {
        $this->postRepository = $postRepository;
        $this->logger = $logger;
    }

    public function getPosts(): array
    {
        $posts = $this->postRepository->getAllPosts();

        return array_map(function ($post) {
            $result = new PostDto();

            $result->title = $post->title;
            $result->content = $post->content;
            $result->slug = $post->slug;

            $author = new UserDto();
            $author->username = $post->author->username;
            $author->profile_picture = $post->author->profile_picture;
            $result->author = $author;

            return $result;
        }, $posts);
    }

    public function getPostBySlug(string $slug): PostDto
    {
        $post = $this->postRepository->getPostBySlug($slug);

        $result = new PostDto();

        $this->logger->info(var_export($post, true));

        $result->title = $post->title;
        $result->content = $post->content;

        $author = new UserDto();
        $author->username = $post->author->username;
        $author->profile_picture = $post->author->profile_picture;
        $result->author = $author;

        $result->slug = $post->slug;

        return $result;
    }
}