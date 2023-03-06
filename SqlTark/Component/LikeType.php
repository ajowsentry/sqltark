<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum LikeType: int
{
    /**
     * Like clause
     */
    case Like = 0;

    /**
     * Starts clause
     */
    case Starts = 1;

    /**
     * Ends clause
     */
    case Ends = 2;

    /**
     * Contains clause
     */
    case Contains = 3;
}
