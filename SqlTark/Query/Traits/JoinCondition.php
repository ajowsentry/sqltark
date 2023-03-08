<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Utilities\Helper;

trait JoinCondition
{
    use Condition;

    /**
     * {@inheritdoc}
     */
    public function on(mixed $left, string $operator, mixed $right): static
    {
        return $this->compare($left, $operator, Helper::resolveExpression($right, true));
    }

    /**
     * {@inheritdoc}
     */
    public function orOn(mixed $left, string $operator, mixed $right): static
    {
        return $this->or()->on($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function notOn(mixed $left, string $operator, mixed $right): static
    {
        return $this->not()->on($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotOn(mixed $left, string $operator, mixed $right): static
    {
        return $this->or()->not()->on($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onEquals(mixed $left, mixed $right): static
    {
        return $this->on($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnEquals(mixed $left, mixed $right): static
    {
        return $this->or()->on($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function notOnEquals(mixed $left, mixed $right): static
    {
        return $this->on($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotOnEquals(mixed $left, mixed $right): static
    {
        return $this->or()->on($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onGreaterThan(mixed $left, mixed $right): static
    {
        return $this->on($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnGreaterThan(mixed $left, mixed $right): static
    {
        return $this->or()->on($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onGreaterEquals(mixed $left, mixed $right): static
    {
        return $this->on($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnGreaterEquals(mixed $left, mixed $right): static
    {
        return $this->or()->on($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onLesserThan(mixed $left, mixed $right): static
    {
        return $this->on($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnLesserThan(mixed $left, mixed $right): static
    {
        return $this->or()->on($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onLesserEquals(mixed $left, mixed $right): static
    {
        return $this->on($left, '<=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnLesserEquals(mixed $left, mixed $right): static
    {
        return $this->or()->on($left, '<=', $right);
    }
}