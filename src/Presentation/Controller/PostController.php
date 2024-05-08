<?php

namespace App\Presentation\Controller;

use App\Application\Contracts\IPostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController {
    private readonly IPostService $postService;

    public function __construct(
        IPostService $postService,
    )
    {
        $this->postService = $postService;
    }

    #[Route('/posts', methods: 'GET')]
    public function getUsers(): Response
    {
        return new Response(
            json_encode($this->postService->getPosts())
        );
    }

    #[Route('/posts/{slug}', methods: 'GET')]
    public function getUserByUsername(string $slug): Response
    {
        return new Response(
            json_encode($this->postService->getPostBySlug($slug))
        );
    }
}