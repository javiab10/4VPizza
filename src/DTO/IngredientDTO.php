<?php

namespace App\Dto;

class IngredientDto
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}