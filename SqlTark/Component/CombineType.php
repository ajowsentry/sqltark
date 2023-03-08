<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum CombineType: int
{
    /**
     * Union Clause
     */
    case Union = 1;

    /**
     * Except Clause
     */
    case Except = 2;

    /**
     * Intersect Clause
     */
    case Intersect = 3;

    public function syntaxOf(): string
    {
        return strtoupper($this->name);
    }
}
