<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use SplFixedArray;
use SqlTark\Query;
use SqlTark\Helper;
use SqlTark\Expressions;
use InvalidArgumentException;
use SqlTark\Component\FromClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\RawFromClause;
use SqlTark\Expressions\BaseExpression;
use SqlTark\Component\AdHocTableFromClause;

/**
 * @method static fromAdHoc(string $alias, iterable<string> $columns, iterable<mixed> $values)
 * @method static fromAdHoc(string $alias, array<string,mixed>[] $pairs)
 */
trait From
{
    /**
     * @var ?string $alias
     */
    protected ?string $alias;

    /**
     * @return ?string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param ?string $alias
     * @return void
     */
    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @param ?string $alias
     * @return static Self object
     */
    public function alias(?string $alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @param string|Query|(Closure(Query):void) $table
     * @return static Self object
     */
    public function from(string|Query|Closure $table, ?string $alias = null): static
    {
        $table = Helper::resolveQuery($table, $this);

        if (!is_string($table) && !($table instanceof Query)) {
            $class = Helper::getType($table);
            throw new InvalidArgumentException("Could not resolve '$class' as table");
        }

        if ($table instanceof Query) {
            if (empty($alias ?: $table->getAlias())) {
                throw new InvalidArgumentException(
                    "No Alias found for sub query from"
                );
            }
        }

        $component = new FromClause;
        $component->setTable($table);
        $component->setAlias($alias);

        return $this->addOrReplaceComponent(ComponentType::From, $component);
    }

    /**
     * @return static Self object
     */
    public function fromAdHoc(string $alias, iterable $columns, ?iterable $values = null): static
    {
        $columnCount = null;
        $resolvedColumns = null;
        if (func_num_args() == 2) {
            $resolvedColumns = [];
            $columnCount = 0;
            foreach (reset($columns) as $column => $value) {
                array_push($resolvedColumns, $column);
                $columnCount++;
            }

            $values = $columns;
            $columns = $resolvedColumns;
        }

        $columnCount ??= Helper::countIterable($columns);
        if ($columnCount == 0) {
            throw new InvalidArgumentException(
                "Could not create ad hoc table with no columns"
            );
        }

        if (is_null($resolvedColumns)) {
            $resolvedColumns = [];
            foreach ($columns as $column) {
                if (!is_scalar($column)) {
                    $class = Helper::getType($column);
                    throw new InvalidArgumentException(
                        "Columns must be scalar. '{$class}' found"
                    );
                }

                array_push($resolvedColumns, (string) $column);
            }
        }

        $rowsCount = Helper::countIterable($values);
        if ($rowsCount == 0) {
            throw new InvalidArgumentException(
                "Could not create ad hoc table with no rows"
            );
        }

        $resolvedRows = [];
        foreach ($values as $row) {
            $resolvedRow = [];
            $columnIndex = 0;
            foreach ($row as $value) {
                if ($columnIndex >= $columnCount) {
                    throw new InvalidArgumentException(
                        "Array values count must same with columns count."
                    );
                }
                array_push($resolvedRow, Helper::resolveLiteral($value, 'value'));
                $columnIndex++;
            }

            if ($columnIndex != $columnCount) {
                throw new InvalidArgumentException(
                    "Array values count must same with columns count."
                );
            }

            array_push($resolvedRows, $resolvedRow);
        }

        $component = new AdHocTableFromClause;
        $component->setAlias($alias);
        $component->setColumns($resolvedColumns);
        $component->setValues($resolvedRows);

        return $this->addOrReplaceComponent(ComponentType::From, $component);
    }

    /**
     * @return $this Self object
     */
    public function fromRaw(string $expression, ...$bindings)
    {
        $resolvedBindings = new SplFixedArray(count($bindings));
        foreach ($bindings as $index => $item) {
            if (is_scalar($item) || is_null($item) || $item instanceof \DateTime) {
                $resolvedBindings[$index] = Expressions::literal($item);
            } elseif ($item instanceof BaseExpression) {
                $resolvedBindings[$index] = $item;
            } else {
                $class = Helper::getType($item);
                throw new InvalidArgumentException(
                    "Could not resolve '$class' as binding."
                );
            }
        }

        $component = new RawFromClause;

        $component->setExpression($expression);
        $component->setBindings($resolvedBindings);

        return $this->addOrReplaceComponent(ComponentType::From, $component);
    }
}
