<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SplFixedArray;
use SqlTark\Expressions\BaseExpression;
use SqlTark\Helper;

class RawCondition extends AbstractCondition
{
    /**
     * @var string $expression
     */
    protected $expression;

    /**
     * @var list<BaseExpression> $bindings
     */
    protected $bindings;

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
     * @return list<BaseExpression>
     */
    public function getBindings(): iterable
    {
        return $this->bindings;
    }

    /**
     * @param list<BaseExpression> $value
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
        $this->bindings = Helper::cloneObject($this->bindings);
    }
}
