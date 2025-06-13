<?php

namespace App\Service;

use App\DTO\PizzaDTO;
use App\Entity\Pizza;
use App\Repository\PizzaRepository;

class PizzaService
{
    public function __construct(private readonly PizzaRepository $pizzaRepository) {}

    /**
     * @return PizzaDTO[]
     */
    public function getFilteredPizzas(?string $title, ?string $ingredients): array
    {
        $allPizzas = $this->pizzaRepository->findAll();
        $ingredientList = [];

        if ($ingredients) {
            $ingredientList = array_map(
                fn ($i) => strtolower(trim($i)),
                explode(',', $ingredients)
            );
        }

        $result = [];

        /** @var Pizza $pizza */
        foreach ($allPizzas as $pizza) {

            //Filtramos por nombre de la pizza
            if ($title && !str_contains(strtolower($pizza->getTitle()), strtolower($title))) {
                continue;
            }

            // Filtramos por ingredientes de la pizza
            if ($ingredientList) {
                $hasMatchingIngredient = false;
                foreach ($pizza->getIngredients() as $ingredient) {
                    foreach ($ingredientList as $search) {
                        if (str_contains(strtolower($ingredient->getName()), $search)) {
                            $hasMatchingIngredient = true;
                            break 2;
                        }
                    }
                }

                if (!$hasMatchingIngredient) {
                    continue;
                }
            }

            // Construimos el DTO a devolver
            $okCeliacs = true;
            $ingredientArray = [];

            foreach ($pizza->getIngredients() as $ingredient) {
                $ingredientArray[] = ['name' => $ingredient->getName()];
                if ($ingredient->isOkCeliacs() !== true) {
                    $okCeliacs = false;
                }
            }

            $result[] = new PizzaDTO(
                $pizza->getId(),
                $pizza->getTitle(),
                $pizza->getImage(),
                $pizza->getPrice(),
                $okCeliacs,
                $ingredientArray
            );
        }

        return $result;
    }
}