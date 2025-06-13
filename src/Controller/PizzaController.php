<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/pizza', name: 'api_pizza_')]
final class PizzaController extends AbstractController
{
    #[Route('/filteredList', name: 'filtered_list', methods: ['GET'])]
    public function getFilteredPizzas(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PizzaController.php',
        ]);
    }
}
