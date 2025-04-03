<?php

namespace App\Controller;

use App\Controller\Services\OrderServiceInterface;
use App\Enums\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/order')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class OrderController extends AbstractController
{
    private OrderServiceInterface $orderService;
    private SerializerInterface $serializer;

    public function __construct(OrderServiceInterface $orderService, SerializerInterface $serializer)
    {
        $this->orderService = $orderService;
        $this->serializer = $serializer;
    }

    #[Route('', name: 'app_order_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // TODO: maybe refactor the logic into a global exception handler
        try {
            $data = json_decode($request->getContent(), true);
            $trade = $this->orderService->create($data['userId'], $data['amount'], $data['price'], OrderType::from($data['type']), $data['timestamp']);
            $serializedTrade = $this->serializer->normalize($trade);
            return $this->json($serializedTrade, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
