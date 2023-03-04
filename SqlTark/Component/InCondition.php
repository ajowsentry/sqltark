<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;
use SqlTark\Expressions\BaseExpression;

class InCondition extends AbstractCondition
{
    /**
     * @var BaseExpression|Query $column
     */
    protected $column;

    /**
     * @var list<BaseExpression>|Query $values
     */
    protected iterable|Query $values;

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
    public function setColumn(BaseExpression|Query $value): void
    {
        $this->column = $value;
    }

    /**
     * @return list<BaseExpression>|Query
     */
    public function getValues(): iterable|Query
    {
        return $this->values;
    }

    /**
     * @param list<BaseExpression>|Query
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
        $this->column = Helper::cloneObject($this->column);
        $this->values = Helper::cloneObject($this->values);
    }
}
