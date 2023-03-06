<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum ComponentType: int
{
    /**
     * Select clause
     */
    case Select = 1;

    /**
     * Aggregate clause
     */
    case Aggregate = 2;

    /**
     * From clause
     */
    case From = 3;

    /**
     * Join clause
     */
    case Join = 4;

    /**
     * Where clause
     */
    case Where = 5;

    /**
     * Group by clause
     */
    case GroupBy = 6;

    /**
     * Having clause
     */
    case Having = 7;

    /**
     * Window clause
     */
    case Window = 8;

    /**
     * Partition clause
     */
    case Partition = 9;

    /**
     * Frame clause
     */
    case Frame = 10;

    /**
     * Order by clause
     */
    case OrderBy = 11;

    /**
     * Limit clause
     */
    case Limit = 12;

    /**
     * Offset clause
     */
    case Offset = 13;

    /**
     * Combine clause
     */
    case Combine = 14;

    /**
     * Common table expression clause
     */
    case CTE = 15;

    /**
     * Insert clause
     */
    case Insert = 16;

    /**
     * Update clause
     */
    case Update = 17;
}