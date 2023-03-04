<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Helper;
use SqlTark\Expressions\BaseExpression;
use SqlTark\Query;

class RawFromClause extends AbstractFrom
{
    /**
     * @var string $expression
     */
    protected $expression;

    /**
     * @var list<BaseExpression|Query> $bindings
     */
    protected $bindings;

    /**
     * @return string
     */
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
     * @return list<BaseExpression|Query>
     */
    public function getBindings(): iterable
    {
        return $this->bindings;
    }

    /**
     * @param list<BaseExpression|Query> $value
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
