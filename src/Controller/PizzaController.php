<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PizzaService;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\DTO\OrderDTO;

#[Route('/api/pizza', name: 'api_pizza_')]
final class PizzaController extends AbstractController
{

    public function __construct(private PizzaService $pizzaService) {}

    #[Route('/filteredList', name: 'filtered_list', methods: ['GET'])]
    public function getFilteredPizzas(Request $request): JsonResponse
    {
        $title = $request->query->get('title');
        $ingredients = $request->query->get('ingredients');



        $pizzas = $this->pizzaService->getFilteredPizzas($title, $ingredients);
        
        return $this->json($pizzas);
    }

    #[Route('/placeOrder', name: 'placeOrder', methods: ['POST'])]
    public function PlaceOrder(Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            /** @var OrderDTO $orderRequest */
            $orderRequest = $serializer->deserialize($request->getContent(), OrderDTO::class, 'json');
        } catch (NotEncodableValueException $e) {
            throw new BadRequestHttpException('Invalid JSON format: ' . $e->getMessage());
        }

        $responseDto = $this->pizzaService->placeOrder($orderRequest);

        if (isset($responseDto['code'])) {
            return $this->json($responseDto, 400);
        }

        return $this->json($responseDto, 201); 
    }
}
