<?php
declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;
use SqlTark\Expressions\BaseExpression;

class OrderClause extends AbstractOrder
{
    /**
     * @var BaseExpression|Query $column
     */
    protected BaseExpression|Query $column;

    /**
     * @var bool
     */
    protected bool $ascending = true;

    /**
     * @return BaseExpression|Query
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param BaseExpression|Query $value
     * @return void
     */
    public function setColumn($value): void
    {
        $this->column = $value;
    }

    /**
     * @return bool
     */
    public function isAscending(): bool
    {
        return $this->ascending;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setAscending(bool $value): void
    {
        $this->ascending = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->column = Helper::cloneObject($this->column);
    }
}