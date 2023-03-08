<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class RawCondition extends AbstractCondition
{
    /**
     * @var string $expression
     */
    protected string $expression;

    /**
     * @var list<AbstractExpression> $bindings
     */
    protected iterable $bindings;

    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setExpression(string $value): void
    {
        $this->expression = $value;
    }

    /**
     * @return list<AbstractExpression>
     */
    public function getBindings(): iterable
    {
        return $this->bindings;
    }

    /**
     * @param list<AbstractExpression> $value
     * @return void
     */
    public function setBindings(iterable $value): void
    {
        $this->bindings = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->bindings = Helper::clone($this->bindings);
    }
}
