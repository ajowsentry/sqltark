<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;
use SqlTark\Expressions\BaseExpression;

class AdHocTableFromClause extends AbstractFrom
{
    /**
     * @var list<string> $columns
     */
    protected iterable $columns;

    /**
     * @var list<list<BaseExpression|Query>> $values
     */
    protected iterable $values;

    /**
     * @return list<string>
     */
    public function getColumns(): iterable
    {
        return $this->columns;
    }

    /**
     * @param list<string> $value
     * @return void
     */
    public function setColumns(iterable $value): void
    {
        $this->columns = $value;
    }

    /**
     * @return list<list<BaseExpression|Query>>
     */
    public function getValues(): iterable
    {
        return $this->values;
    }

    /**
     * @param list<list<BaseExpression|Query>> $value
     * @return void
     */
    public function setValues(iterable $value): void
    {
        $this->values = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->columns = Helper::cloneObject($this->columns);
        $this->values = Helper::cloneObject($this->values);
    }
}
