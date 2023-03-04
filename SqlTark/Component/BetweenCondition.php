<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;
use SqlTark\Expressions\BaseExpression;

class BetweenCondition extends AbstractCondition
{
    /**
     * @var BaseExpression|Query $column
     */
    protected BaseExpression|Query $column;

    /**
     * @var BaseExpression|Query $lower
     */
    protected BaseExpression|Query $lower;

    /**
     * @var BaseExpression|Query $higher
     */
    protected BaseExpression|Query $higher;

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
    public function setColumn($value): void
    {
        $this->column = $value;
    }

    /**
     * @return BaseExpression|Query
     */
    public function getLower(): BaseExpression|Query
    {
        return $this->lower;
    }

    /**
     * @param BaseExpression|Query $value
     * @return void
     */
    public function setLower($value): void
    {
        $this->lower = $value;
    }

    /**
     * @return BaseExpression|Query
     */
    public function getHigher(): BaseExpression|Query
    {
        return $this->higher;
    }

    /**
     * @param BaseExpression|Query $value
     * @return void
     */
    public function setHigher($value): void
    {
        $this->higher = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->lower = Helper::cloneObject($this->lower);
        $this->column = Helper::cloneObject($this->column);
        $this->higher = Helper::cloneObject($this->higher);
    }
}
