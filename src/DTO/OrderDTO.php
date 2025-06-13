<?php

namespace App\DTO;

class OrderDTO
{
    /** @var NewPizzaOrderDTO[] */
    public array $pizzas_order;

    public string $delivery_time;
    public string $delivery_address;

    public PaymentDTO $payment;
}