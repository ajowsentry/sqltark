<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

use SqlTark\Query;

class Raw extends BaseExpression
{
    /**
     * @var string
     */
    protected string $expression;

    /**
     * @var iterable<BaseExpression|Query> $bindings
     */
    protected iterable $bindings;

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setExpression(string $value): static
    {
        $this->expression = $value;
        return $this;
    }

    /**
     * @return iterable<BaseExpression|Query>
     */
    public function getBindings(): iterable
    {
        return $this->bindings;
    }

    /**
     * @param iterable<BaseExpression|Query> $value
     * @return static
     */
    public function setBindings(iterable $value): static
    {
        $this->bindings = $value;
        return $this;
    }

    /**
     * @param string $expression
     * @param iterable<BaseExpression|Query> $bindings
     */
    public function __construct(string $expression, iterable $bindings = [])
    {
        $this->expression = $expression;
        $this->bindings = $bindings;
    }
}
