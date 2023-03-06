<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Helper;
use SqlTark\Query\Join;

class JoinClause extends AbstractJoin
{
    /**
     * @var Join
     */
    protected Join $join;

    /**
     * @return Join
     */
    public function getJoin(): Join
    {
        return $this->join;
    }

    /**
     * @param Join $value
     * @return void
     */
    public function setJoin(Join $value): void
    {
        $this->join = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->join = Helper::cloneObject($this->join);
    }
}
