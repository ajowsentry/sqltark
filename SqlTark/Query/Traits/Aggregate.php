<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AggregateClause;
use SqlTark\Expressions\AbstractExpression;

trait Aggregate
{
    /**
     * @param string $type Aggregate type
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asAggregate(string $type, mixed $column): static
    {
        $column = Helper::resolveExpression($column, true);

        $this->setMethod(MethodType::Aggregate);

        $component = new AggregateClause;
        $component->setType($type);
        $component->setColumn($column);

        return $this->addOrReplaceComponent(ComponentType::Aggregate, $component);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asCount(mixed $column): static
    {
        return $this->asAggregate('COUNT', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asAvg(mixed $column): static
    {
        return $this->asAggregate('AVG', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asAverage(mixed $column): static
    {
        return $this->asAggregate('AVG', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asSum(mixed $column): static
    {
        return $this->asAggregate('SUM', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asMax(mixed $column): static
    {
        return $this->asAggregate('MAX', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asMin(mixed $column): static
    {
        return $this->asAggregate('MIN', $column);
    }
}