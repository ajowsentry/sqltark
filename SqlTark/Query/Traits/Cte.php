<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use SqlTark\Query;
use SqlTark\Utilities\Helper;
use InvalidArgumentException;
use SqlTark\Component\FromClause;
use SqlTark\Component\ComponentType;

trait Cte
{
    /**
     * @param (Closure(Query):void)|Query $query
     * @param ?string $alias
     * @return static Self object
     */
    public function with(Closure|Query $query, ?string $alias = null): static
    {
        $query = Helper::resolveQuery($query, $this);

        $alias = $alias ?? $query->getAlias();
        if(empty($alias)) {
            throw new InvalidArgumentException(
                "No alias found for CTE query"
            );
        }

        $component = new FromClause;
        $component->setTable($query);
        $component->setAlias($alias);

        return $this->addComponent(ComponentType::CTE, $component);
    }
}