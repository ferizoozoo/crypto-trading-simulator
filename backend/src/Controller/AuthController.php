<?php

namespace App\Controller;

use App\Controller\Services\UserServiceInterface;
use App\Entities\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/auth')]
#[IsGranted('PUBLIC_ACCESS')]
class AuthController extends AbstractController
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/register', name: 'app_user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        // TODO: maybe refactor the logic into a global exception handler
        try {
            $data = json_decode($request->getContent(), true);
            $this->userService->register(new User($data['email'], $data['password']));
            return $this->json([], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/login', name: 'app_user_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?UserInterface $user): JsonResponse
    {
        // TODO: maybe refactor the logic into a global exception handler
        try {
            if (!$user) {
                return $this->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
            }
            $token = $this->userService->authenticate($user);
            return $this->json(['jwt-token' => $token], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
