<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Component\CombineType;
use SqlTark\Component\CombineClause;
use SqlTark\Component\ComponentType;

trait Combine
{
    /**
     * @param (Closure(Query):void)|Query $query
     * @param CombineType $operation
     * @param bool $all Combine all
     * @return static Self object
     */
    public function combine(Closure|Query $query, CombineType $operation = CombineType::Union, bool $all = false)
    {
        $component = new CombineClause;
        $component->setQuery(Helper::resolveQuery($query, $this));
        $component->setOperation($operation);
        $component->setAll($all);

        return $this->addComponent(ComponentType::Combine, $component);
    }

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function union(Closure|Query $query)
    {
        return $this->combine($query, CombineType::Union, false);
    }

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function unionAll(Closure|Query $query)
    {
        return $this->combine($query, CombineType::Union, true);
    }

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function except(Closure|Query $query)
    {
        return $this->combine($query, CombineType::Except, false);
    }

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function exceptAll(Closure|Query $query)
    {
        return $this->combine($query, CombineType::Except, true);
    }

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function intersect(Closure|Query $query)
    {
        return $this->combine($query, CombineType::Intersect, false);
    }

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function intersectAll(Closure|Query $query)
    {
        return $this->combine($query, CombineType::Intersect, true);
    }
}