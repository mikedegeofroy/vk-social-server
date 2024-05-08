<?php

namespace App\Presentation\Controller;

use App\Application\Contracts\IAuthService;
use App\Application\Models\Auth\RegisterUserDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class AuthController extends AbstractController
{
    private readonly IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    #[Route('/auth/login', methods: 'POST')]
    public function requestLogin(#[MapQueryParameter] string $email): Response
    {
        return new Response($this->authService->requestLogin($email));
    }

    #[Route('/auth/verify', methods: 'GET')]
    public function verifyLogin(#[MapQueryParameter] string $code): Response
    {
        return new Response(json_encode($this->authService->verifyLogin($code)));
    }

    #[Route('/auth/register', methods: 'POST')]
    public function registerUser(#[MapRequestPayload] RegisterUserDto $userDto): Response
    {
        return new Response(json_encode($this->authService->registerUser($userDto)));
    }
}