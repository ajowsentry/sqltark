<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum ComponentType: int
{
    case Select = 1;
    case Aggregate = 2;
    case From = 3;
    case Join = 4;
    case Where = 5;
    case GroupBy = 6;
    case Having = 7;
    case Window = 8;
    case Partition = 9;
    case Frame = 10;
    case OrderBy = 11;
    case Limit = 12;
    case Offset = 13;
    case Combine = 14;
    case CTE = 15;
    case Insert = 16;
    case Update = 17;
}