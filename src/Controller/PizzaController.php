<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PizzaService;

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
}
