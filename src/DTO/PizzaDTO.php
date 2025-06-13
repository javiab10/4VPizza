<?php

namespace App\Dto;

class PizzaResponseDto
{
    public int $id;
    public string $title;
    public string $image;
    public float $price;
    public bool $okCeliacs;
    /** @var array<int, array{name: string}> */
    public array $ingredients = [];

    public function __construct(
        int $id,
        string $title,
        string $image,
        float $price,
        bool $okCeliacs,
        array $ingredients
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->price = $price;
        $this->okCeliacs = $okCeliacs;
        $this->ingredients = $ingredients;
    }
}