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
        $userId = $this->getUser()->getId();
        return $this->json($this->userService->getUserBalance($userId), Response::HTTP_OK);
    }

    #[Route('/balance/withdraw', name: 'app_user_balance_withdraw', methods: ['POST'])]
    public function withdraw(Request $request): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $amount = json_decode($request->getContent(), true);
        if ($amount < 0) return $this->json(['message' => 'Amount must be positive'], Response::HTTP_BAD_REQUEST);
        $this->userService->updateBalance((int)$userId, -$amount);
        return $this->json([], Response::HTTP_OK);
    }

    #[Route('/balance/deposit', name: 'app_user_balance_deposit', methods: ['POST'])]
    public function deposit(Request $request): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $amount = json_decode($request->getContent(), true);
        if ($amount < 0) return $this->json(['message' => 'Amount must be positive'], Response::HTTP_BAD_REQUEST);
        $this->userService->updateBalance((int)$userId, $amount);
        return $this->json([], Response::HTTP_OK);
    }
}
