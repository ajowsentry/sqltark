<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;
use SqlTark\Expressions\BaseExpression;

class AggregateClause extends AbstractComponent
{
    /**
     * @var string $type
     */
    protected string $type;

    /**
     * @var Query|BaseExpression
     */
    protected Query|BaseExpression $column;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setType(string $value): void
    {
        $this->type = $value;
    }

    /**
     * @return Query|BaseExpression
     */
    public function getColumn(): Query|BaseExpression
    {
        return $this->column;
    }

    /**
     * @param Query|BaseExpression $value
     * @return void
     */
    public function setColumn(Query|BaseExpression $value): void
    {
        $this->column = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->column = Helper::cloneObject($this->column);
    }
}