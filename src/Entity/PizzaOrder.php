<?php

namespace App\Entity;

use App\Repository\PizzaOrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaOrderRepository::class)]
class PizzaOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pizza $pizza = null;

    #[ORM\ManyToOne(inversedBy: 'pizzas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $pizzaOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPizza(): ?Pizza
    {
        return $this->pizza;
    }

    public function setPizza(?Pizza $pizza): static
    {
        $this->pizza = $pizza;

        return $this;
    }

    public function getPizzaOrder(): ?Order
    {
        return $this->pizzaOrder;
    }

    public function setPizzaOrder(?Order $pizzaOrder): static
    {
        $this->pizzaOrder = $pizzaOrder;

        return $this;
    }
}
