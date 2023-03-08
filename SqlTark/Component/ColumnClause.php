<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class ColumnClause extends AbstractColumn
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected AbstractExpression|Query $column;

    /**
     * @return AbstractExpression|Query
     */
    public function getColumn(): AbstractExpression|Query
    {
        return $this->column;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setColumn(AbstractExpression|Query $value): void
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