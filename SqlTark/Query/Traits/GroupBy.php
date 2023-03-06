<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use DateTime;
use SqlTark\Helper;
use SqlTark\Expressions;
use InvalidArgumentException;
use SqlTark\Component\RawColumn;
use SqlTark\Query\AbstractQuery;
use SqlTark\Component\ColumnClause;
use SqlTark\Component\ComponentType;
use SqlTark\Expressions\BaseExpression;
use SqlTark\Query\QueryInterface;

trait GroupBy
{
    /**
     * @param null|scalar|DateTime|Query|(Closure(QueryInterface):void) ...$columns
     * @return static Self object
     */
    public function groupBy(mixed ...$columns): static
    {
        if (func_num_args() == 1 && is_iterable(reset($columns))) {
            $columns = reset($columns);
        }

        foreach ($columns as $column) {
            $column = Helper::resolveQuery($column, $this);
            $column = Helper::resolveExpression($column, 'column');

            $component = new ColumnClause;
            $component->setColumn($column);

            /** @var AbstractQuery $this */
            $this->addComponent(ComponentType::GroupBy, $component);
        }

        return $this;
    }

    /**
     * @param string $expression
     * @param null|scalar|DateTime|Query|(Closure(QueryInterface):void) ...$bindings
     * @return static Self object
     */
    public function groupByRaw(string $expression, ...$bindings)
    {
        $resolvedBindings = [];
        foreach ($bindings as $item) {
            if (is_scalar($item) || is_null($item) || $item instanceof \DateTime) {
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

        return $this->addComponent(ComponentType::GroupBy, $component);
    }
}