<?php

namespace App\Strategies\Listagens;

use InvalidArgumentException;

final class StrategyResolver
{
    private array $map = [];

    public function __construct(iterable $strategies){
        foreach($strategies as $stragy){
            $this->map[$stragy->key()] = $stragy;
        }
    }

    public function resolve(string $tipo)
    {
        return $this->map[$tipo] ?? throw new InvalidArgumentException("Tipo de listagem inválido: {$tipo}");
    }
}
