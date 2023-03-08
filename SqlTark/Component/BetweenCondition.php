<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class BetweenCondition extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected AbstractExpression|Query $column;

    /**
     * @var AbstractExpression|Query $lower
     */
    protected AbstractExpression|Query $lower;

    /**
     * @var AbstractExpression|Query $higher
     */
    protected AbstractExpression|Query $higher;

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
    public function setColumn($value): void
    {
        $this->column = $value;
    }

    /**
     * @return AbstractExpression|Query
     */
    public function getLower(): AbstractExpression|Query
    {
        return $this->lower;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setLower($value): void
    {
        $this->lower = $value;
    }

    /**
     * @return AbstractExpression|Query
     */
    public function getHigher(): AbstractExpression|Query
    {
        return $this->higher;
    }

    /**
     * @param AbstractExpression|Query $value
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
        $this->lower = Helper::clone($this->lower);
        $this->column = Helper::clone($this->column);
        $this->higher = Helper::clone($this->higher);
    }
}
