<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Helper;
use SqlTark\Query;
use SqlTark\Expressions\BaseExpression;

class ColumnClause extends AbstractColumn
{
    /**
     * @var BaseExpression|Query
     */
    protected BaseExpression|Query $column;

    /**
     * @return BaseExpression|Query
     */
    public function getColumn(): BaseExpression|Query
    {
        return $this->column;
    }

    /**
     * @param BaseExpression|Query $value
     * @return void
     */
    public function setColumn(BaseExpression|Query $value): void
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