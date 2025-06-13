<?php

namespace App\DTO;

class OrderDTO
{
    /** @var PizzaOrderInputDto[] */
    public array $pizzas_order;

    public string $delivery_time;
    public string $delivery_address;

    public PaymentDto $payment;
}