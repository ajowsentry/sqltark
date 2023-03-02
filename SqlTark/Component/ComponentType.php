<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum ComponentType
{
    /**
     * Select query component
     * Ex. SELECT * FROM `table`
     */
    case Select;
    
    /**
     * Select aggregate query component
     * Ex. SELECT MAX(`id`) FROM `table`
     */
    case Aggregate;
    
    case From;
    case Join;
    case Where;
    case GroupBy;
    case Having;
    case Window;
    case Partition;
    case Frame;
    case OrderBy;
    case Limit;
    case Offset;
    case Combine;
    case CTE;
    case Insert;
    case Update;
}