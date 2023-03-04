<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Component\LimitClause;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\ComponentType;

trait Paging
{
    /**
     * @param int $limit
     * @return static Self object
     */
    public function limit(int $limit): static
    {
        $component = new LimitClause;
        $component->setLimit($limit);

        return $this->addOrReplaceComponent(ComponentType::Limit, $component);
    }

    /**
     * @param int $offset
     * @return static Self object
     */
    public function offset(int $offset): static
    {
        $component = new OffsetClause;
        $component->setOffset($offset);

        return $this->addOrReplaceComponent(ComponentType::Offset, $component);
    }

    /**
     * @param int $take
     * @return static Self object
     */
    public function take(int $take): static
    {
        return $this->limit($take);
    }

    /**
     * @param int $skip
     * @return static Self object
     */
    public function skip(int $skip): static
    {
        return $this->offset($skip);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return static Self object
     */
    public function forPage(int $page, int $perPage = 20): static
    {
        return $this->skip(($page - 1) * $perPage)->take($perPage);
    }
}