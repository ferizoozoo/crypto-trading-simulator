<?php

namespace App\Controller;

use App\Controller\Services\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserController extends AbstractController
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/balance', name: 'app_user_balance', methods: ['GET'])]
    public function getBalance(Request $request): JsonResponse
    {
        $userId = $request->getContent();
        return $this->json($this->userService->getUserBalance($userId), Response::HTTP_OK);
    }
}
