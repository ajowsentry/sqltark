<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Utilities\Helper;
use SqlTark\Component\RawOrder;
use SqlTark\Component\OrderClause;
use SqlTark\Component\RandomOrder;
use SqlTark\Component\ComponentType;
use SqlTark\Expressions\AbstractExpression;

trait Order
{
    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param bool $isAscending
     * @return static Self object
     */
    public function orderBy(mixed $column, bool $isAscending = true): static
    {
        $column = Helper::resolveExpression($column, true);

        $component = new OrderClause;
        $component->setColumn($column);
        $component->setAscending($isAscending);

        return $this->addComponent(ComponentType::OrderBy, $component);
    }

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function orderByDesc(mixed $column): static
    {
        return $this->orderBy($column, false);
    }

    /**
     * @return static Self object
     */
    public function orderByRandom(): static
    {
        return $this->addComponent(ComponentType::OrderBy, new RandomOrder);
    }

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression ...$bindings
     * @return static Self object
     */
    public function orderRaw(string $expression, mixed ...$bindings): static
    {
        $component = new RawOrder;
        $component->setExpression($expression);
        $component->setBindings(Helper::resolveExpressionList($bindings));

        return $this->addComponent(ComponentType::OrderBy, $component);
    }
}
