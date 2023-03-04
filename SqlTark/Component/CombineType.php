<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum CombineType: int
{
    case Union = 1;
    case Except = 2;
    case Intersect = 3;
}
