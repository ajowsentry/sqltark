<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use DateTime;
use SqlTark\Helper;
use SqlTark\Expressions;
use InvalidArgumentException;
use SqlTark\Component\RawColumn;
use SqlTark\Component\ColumnClause;
use SqlTark\Component\ComponentType;
use SqlTark\Expressions\BaseExpression;

trait Select
{
    /**
     * @var bool $distinct
     */
    protected $distinct = false;

    /**
     * @return bool
     */
    public function isDistict(): bool
    {
        return $this->distinct;
    }

    /**
     * @param bool $value
     * @return static Self object
     */
    public function distinct(bool $value = true): static
    {
        $this->distinct = $value;
        return $this;
    }

    /**
     * @param null|scalar|DateTime|Query|(Closure(QueryInterface):void) ...$columns
     * @return static Self object
     */
    public function select(mixed ...$columns): static
    {
        if (func_num_args() == 1 && is_iterable($columns[0])) {
            $columns = $columns[0];
        }

        foreach ($columns as $column) {
            $column = Helper::resolveQuery($column, $this);
            $column = Helper::resolveExpression($column, 'column');

            $component = new ColumnClause;
            $component->setColumn($column);

            $this->addComponent(ComponentType::Select, $component);
        }

        return $this;
    }

    /**
     * @param string $expression
     * @param null|scalar|DateTime|Query|(Closure(QueryInterface):void) ...$bindings
     * @return static Self object
     */
    public function selectRaw(string $expression, mixed ...$bindings): static
    {
        $resolvedBindings = [];
        foreach ($bindings as $item) {
            if (is_scalar($item) || is_null($item) || $item instanceof DateTime) {
                array_push($resolvedBindings, Expressions::literal($item));
            } elseif ($item instanceof BaseExpression) {
                array_push($resolvedBindings, $item);
            } else {
                $class = Helper::getType($item);
                throw new InvalidArgumentException(
                    "Could not resolve '{$class}' as binding."
                );
            }
        }

        $component = new RawColumn;

        $component->setExpression($expression);
        $component->setBindings($resolvedBindings);

        return $this->addComponent(ComponentType::Select, $component);
    }
}
