<?php

namespace App\Service;

use App\DTO\PizzaDTO;
use App\DTO\OrderDTO;
use App\Entity\Pizza;
use App\Entity\Order;
use App\Entity\PizzaOrder;
use App\Repository\PizzaRepository;
use Doctrine\ORM\EntityManagerInterface;

class PizzaService
{
    public function __construct(
        private readonly PizzaRepository $pizzaRepository,
        private readonly EntityManagerInterface $em
    ) {}

    /**
     * @return PizzaDTO[]
     */
    public function getFilteredPizzas(?string $title, ?string $ingredients): array
    {
        $allPizzas = $this->pizzaRepository->findAll();
        $ingredientList = [];

        if ($ingredients) {
            $ingredientList = array_map(
                fn($i) => strtolower(trim($i)),
                explode(',', $ingredients)
            );
        }

        $result = [];

        /** @var Pizza $pizza */
        foreach ($allPizzas as $pizza) {

            //Filtramos por nombre de la pizza con método contains
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

    public function placeOrder(OrderDTO $orderRequest)
    {
        // Validación
        $validationError = $this->validateOrder($orderRequest);
        if ($validationError !== null) {
            return $validationError;
        }

        // Crear entidad Order
        $orderEntity = new Order();
        $orderEntity->setDeliveryAddress($orderRequest->delivery_address);
        $orderEntity->setDeliveryTime($orderRequest->delivery_time);
        $orderEntity->setPaymentType($orderRequest->payment->payment_type);
        $orderEntity->setPaymentNumber($orderRequest->payment->number);

        $responsePizzasOrder = [];

        foreach ($orderRequest->pizzas_order as $pizzaOrder) {
            $pizzaEntity = $this->pizzaRepository->find($pizzaOrder->pizza_id);
            if (!$pizzaEntity) {
                return ['code' => 26, 'description' => 'Pizza with id ' . $pizzaOrder->pizza_id . ' not found'];
            }

            // Crear entidad PizzaOrder y asociar
            $orderItem = new PizzaOrder();
            $orderItem->setPizza($pizzaEntity);
            $orderItem->setQuantity($pizzaOrder->quantity);
            $orderItem->setpizzaOrder($orderEntity); // relación bidireccional

            $this->em->persist($orderItem);

            $ingredients = [];
            $okCeliacs = true;
            foreach ($pizzaEntity->getIngredients() as $ingredient) {
                $ingredients[] = ['name' => $ingredient->getName()];
                if (!$ingredient->isOkCeliacs()) {
                    $okCeliacs = false;
                }
            }

            $pizzaDTO = [
                'id' => $pizzaEntity->getId(),
                'title' => $pizzaEntity->getTitle(),
                'image' => $pizzaEntity->getImage(),
                'price' => $pizzaEntity->getPrice(),
                'ok_celiacs' => $okCeliacs,
                'ingredients' => $ingredients,
            ];

            $responsePizzasOrder[] = [
                'quantity' => $pizzaOrder->quantity,
                'pizza_type' => $pizzaDTO,
            ];
        }

        // Persistir y flush pedido
        $this->em->persist($orderEntity);
        $this->em->flush();

        return [
            'id' => $orderEntity->getId(),
            'pizzas_order' => $responsePizzasOrder,
        ];
    }

    private function validateOrder(OrderDTO $order): ?array
    {
        if (empty($order->pizzas_order)) {
            return ['code' => 22, 'description' => 'El pedido debe contener al menos una pizza'];
        }

        foreach ($order->pizzas_order as $pizzaOrder) {
            if ($pizzaOrder->quantity <= 0) {
                return ['code' => 23, 'description' => 'La cantidad de pizzas debe ser mayor que cero'];
            }
        }

        if (empty($order->delivery_address)) {
            return ['code' => 24, 'description' => 'La dirección de entrega es obligatoria'];
        }

        if (empty($order->payment->payment_type) || empty($order->payment->number)) {
            return ['code' => 25, 'description' => 'El tipo de pago y el número de pago son obligatorios'];
        }

        return null;
    }
}
