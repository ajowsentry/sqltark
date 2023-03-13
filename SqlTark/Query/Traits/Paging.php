<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Component\LimitClause;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\ComponentType;

trait Paging
{
    /**
     * @param scalar $limit
     * @return static Self object
     */
    public function limit(mixed $limit): static
    {
        $component = new LimitClause;
        $component->setLimit(intval($limit));

        return $this->addOrReplaceComponent(ComponentType::Limit, $component);
    }

    /**
     * @param scalar $offset
     * @return static Self object
     */
    public function offset(mixed $offset): static
    {
        $component = new OffsetClause;
        $component->setOffset(intval($offset));

        return $this->addOrReplaceComponent(ComponentType::Offset, $component);
    }

    /**
     * @param scalar $take
     * @return static Self object
     */
    public function take(mixed $take): static
    {
        return $this->limit($take);
    }

    /**
     * @param scalar $skip
     * @return static Self object
     */
    public function skip(mixed $skip): static
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