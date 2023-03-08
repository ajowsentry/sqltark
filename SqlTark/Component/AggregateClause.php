<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class AggregateClause extends AbstractComponent
{
    /**
     * @var string $type
     */
    protected string $type;

    /**
     * @var Query|AbstractExpression
     */
    protected Query|AbstractExpression $column;

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
     * @return Query|AbstractExpression
     */
    public function getColumn(): Query|AbstractExpression
    {
        return $this->column;
    }

    /**
     * @param Query|AbstractExpression $value
     * @return void
     */
    public function setColumn(Query|AbstractExpression $value): void
    {
        $this->column = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->column = Helper::clone($this->column);
    }
}