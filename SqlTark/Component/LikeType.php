<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum LikeType: int
{
    case Like = 0;
    case Starts = 1;
    case Ends = 2;
    case Contains = 3;
}
