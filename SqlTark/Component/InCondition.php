<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class InCondition extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected AbstractExpression|Query $column;

    /**
     * @var list<AbstractExpression>|Query $values
     */
    protected iterable|Query $values;

    /**
     * @return AbstractExpression|Query
     */
    public function getColumn()
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
     * @return list<AbstractExpression>|Query
     */
    public function getValues(): iterable|Query
    {
        return $this->values;
    }

    /**
     * @param list<AbstractExpression>|Query $value
     * @return void
     */
    public function setValues(iterable|Query $value): void
    {
        $this->values = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->column = Helper::clone($this->column);
        $this->values = Helper::clone($this->values);
    }
}
