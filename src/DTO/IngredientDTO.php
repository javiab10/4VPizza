<?php

namespace App\DTO;

class IngredientDTO
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}