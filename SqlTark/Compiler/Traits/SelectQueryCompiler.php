<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Component\RawFrom;
use SqlTark\Component\JoinType;
use SqlTark\Component\LikeType;
use SqlTark\Component\RawOrder;
use SqlTark\Component\RawColumn;
use SqlTark\Component\FromClause;
use SqlTark\Component\JoinClause;
use SqlTark\Component\InCondition;
use SqlTark\Component\LimitClause;
use SqlTark\Component\OrderClause;
use SqlTark\Component\RandomOrder;
use SqlTark\Component\AbstractFrom;
use SqlTark\Component\AbstractJoin;
use SqlTark\Component\ColumnClause;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\RawCondition;
use SqlTark\Component\CombineClause;
use SqlTark\Component\CompareClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\LikeCondition;
use SqlTark\Component\NullCondition;
use SqlTark\Component\AbstractColumn;
use SqlTark\Component\GroupCondition;
use SqlTark\Component\ExistsCondition;
use SqlTark\Component\BetweenCondition;
use SqlTark\Component\AbstractCondition;
use SqlTark\Component\AbstractOrder;
use SqlTark\Expressions\AbstractExpression;

trait SelectQueryCompiler
{
    use ExpressionCompiler;

    /**
     * {@inheritdoc}
     */
    public function compileSelectQuery(Query $query): string
    {
        /** @var list<AbstractFrom> $cte */
        $cte = $query->getComponents(ComponentType::CTE);

        /** @var list<AbstractColumn> $selects */
        $selects = $query->getComponents(ComponentType::Select);

        /** @var ?AbstractFrom $from */
        $from = $query->getOneComponent(ComponentType::From);

        /** @var list<AbstractJoin> $joins */
        $joins = $query->getComponents(ComponentType::Join);

        /** @var list<AbstractCondition> $where */
        $where = $query->getComponents(ComponentType::Where);

        /** @var list<AbstractColumn> $groupBy */
        $groupBy = $query->getComponents(ComponentType::GroupBy);

        /** @var list<AbstractCondition> $havings */
        $havings = $query->getComponents(ComponentType::Having);

        /** @var list<AbstractOrder> $orderBy */
        $orderBy = $query->getComponents(ComponentType::OrderBy);

        /** @var list<CombineClause> $combines */
        $combines = $query->getComponents(ComponentType::Combine);

        /** @var ?LimitClause $limit */
        $limit = $query->getOneComponent(ComponentType::Limit);

        /** @var ?OffsetClause $offset */
        $offset = $query->getOneComponent(ComponentType::Offset);

        $result = '';

        $resolvedCte = $this->compileCte($cte);
        if($resolvedCte) {
            $result .= $resolvedCte . ' ';
        }

        $result .= $this->compileSelect($selects, $query->isDistict());

        $resolvedFrom = $this->compileFrom($from);
        if(is_string($resolvedFrom)) {
            $result .= ' FROM ' . $resolvedFrom;
        }

        $resolvedJoin = $this->compileJoin($joins);
        if(is_string($resolvedJoin)) {
            $result .= ' ' . $resolvedJoin;
        }

        $resolvedWhere = $this->compileWhere($where);
        if(is_string($resolvedWhere)) {
            $result .= ' ' . $resolvedWhere;
        }

        $resolvedGroupBy = $this->compileGroupBy($groupBy);
        if(is_string($resolvedGroupBy)) {
            $result .= ' ' . $resolvedGroupBy;
        }

        $resolvedHaving = $this->compileHaving($havings);
        if(is_string($resolvedHaving)) {
            $result .= ' ' . $resolvedHaving;
        }

        $resolvedOrderBy = $this->compileOrderBy($orderBy);
        if(is_string($resolvedOrderBy)) {
            $result .= ' ' . $resolvedOrderBy;
        }

        $resolvedPaging = $this->compilePaging($limit, $offset);
        if(is_string($resolvedPaging)) {
            $result .= ' ' . $resolvedPaging;
        }

        $resolvedCombine = $this->compileCombine($combines);
        if(is_string($resolvedCombine)) {
            $result .= ' ' . $resolvedCombine;
        }

        return $result;
    }

    /**
     * @param list<AbstractColumn> $columns
     * @param bool $isDistinct
     * @return string
     */
    protected function compileSelect(iterable $columns, bool $isDistinct): string
    {
        $result = $this->compileColumns($columns, true);
        if (empty($result)) {
            $result = '*';
        }

        if($isDistinct) {
            $result = "DISTINCT {$result}";
        }

        return "SELECT {$result}";
    }

    /**
     * @param list<AbstractColumn> $columns
     * @param bool $withAlias
     * @return string
     */
    protected function compileColumns(iterable $columns, bool $withAlias): string
    {
        $expressionResolver = function ($expression) use ($withAlias) {

            if ($expression instanceof AbstractExpression) {
                return $this->compileExpression($expression, $withAlias);
            }

            elseif ($expression instanceof Query) {
                $resolvedValue = $this->compileQuery($expression);
                $resolvedValue = "($resolvedValue)";

                if ($withAlias) {
                    $alias = $expression->getAlias();
                    if ($alias) $resolvedValue .= ' AS ' . $this->wrapIdentifier($alias);
                }

                return $resolvedValue;
            }
        };

        $result = '';
        $index = 0;
        foreach ($columns as $column) {
            $resolvedColumn = null;
            if ($column instanceof ColumnClause) {
                $columnContent = $column->getColumn();
                $resolvedColumn = $expressionResolver($columnContent);
            }

            elseif ($column instanceof RawColumn) {
                $resolvedColumn = $this->compileRaw(
                    $column->getExpression(),
                    $column->getBindings()
                );
            }

            if (! is_null($resolvedColumn)) {
                if ($index > 0) $result .= ', ';
                $result .= $resolvedColumn;
            }

            $index++;
        }

        return $result;
    }

    /**
     * @param ?AbstractFrom $table
     * @return string
     */
    protected function compileFrom(?AbstractFrom $table): string
    {
        $result = '';

        if ($table instanceof FromClause) {
            $expression = $table->getTable();
            if (is_string($expression)) {
                $result = $this->compileTable($expression);
            } elseif ($expression instanceof Query) {
                $result = '(' . $this->compileQuery($expression) . ') AS ' . $table->getAlias();
            }
        }
        elseif ($table instanceof RawFrom) {
            $result = $this->compileRaw(
                $table->getExpression(),
                $table->getBindings()
            );
        }

        if (empty($result) && $this->fromTableRequired) {
            $result = $this->dummyTable;
        }

        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function compileTable(string $name): string
    {
        $result = trim($name);
        if (empty($result)) {
            return '';
        }

        $aliasSplit = array_map(
            fn($item) => $this->wrapIdentifier($item),
            Helper::extractAlias($result)
        );

        $result = $aliasSplit[0];
        if (isset($aliasSplit[1])) {
            $result .= ' AS ' . $aliasSplit[1];
        }

        return $result;
    }

    /**
     * @param list<AbstractJoin> $joins
     * @return string
     */
    protected function compileJoin(iterable $joins): string
    {
        $result = '';
        $index = 0;
        foreach ($joins as $component) {
            $resolvedJoin = null;
            if ($component instanceof JoinClause) {
                $join = $component->getJoin();

                /** @var ?AbstractFrom $table */
                $table = $join->getOneComponent(ComponentType::From);
                $resolvedTable = $this->compileFrom($table);

                $resolvedJoin = $join->getType()->syntaxOf() . ' ' . $resolvedTable;
                /** Natural and cross join doesn't need on condition */
                if (!in_array($join->getType(), [JoinType::CrossJoin, JoinType::NaturalJoin])) {
                    /** @var list<AbstractCondition> $conditions */
                    $conditions = $join->getComponents(ComponentType::Where);
                    $resolvedCondition = $this->compileConditions($conditions);
                    if (!empty($resolvedCondition)) {
                        $resolvedJoin .= ' ON ' . $resolvedCondition;
                    }
                }
            }

            if (!empty($resolvedJoin)) {
                if ($index > 0) $result .= ' ';
                $result .= $resolvedJoin;
            }
            $index++;
        }

        return $result;
    }

    /**
     * @param list<AbstractCondition> $conditions
     * @param ComponentType $type
     * @return string
     */
    protected function compileConditions(iterable $conditions, ComponentType $type = ComponentType::Where): string
    {
        $expressionResolver = function ($expression, $wrapQuery = true) {

            if ($expression instanceof AbstractExpression) {
                return $this->compileExpression($expression, false);
            }
            elseif ($expression instanceof Query) {
                $resolvedValue = $this->compileQuery($expression);
                if ($wrapQuery) $resolvedValue = "($resolvedValue)";
                return $resolvedValue;
            }
        };

        $result = '';
        $index = 0;
        foreach ($conditions as $condition) {
            $resolvedCondition = null;
            if ($condition instanceof CompareClause) {
                $left = $condition->getLeft();
                $right = $condition->getRight();
                $operator = $condition->getOperator();

                $resolvedLeft = $expressionResolver($left);
                $resolvedRight = $expressionResolver($right);

                $resolvedCondition = "$resolvedLeft $operator $resolvedRight";
                if ($condition->getNot()) {
                    $resolvedCondition = "NOT ($resolvedCondition)";
                }
            }
            elseif ($condition instanceof BetweenCondition) {
                $column = $condition->getColumn();
                $lower = $condition->getLower();
                $higher = $condition->getHigher();

                $resolvedColumn = $expressionResolver($column);
                $resolvedLower = $expressionResolver($lower);
                $resolvedHigher = $expressionResolver($higher);

                $resolvedCondition = $resolvedColumn;
                if ($condition->getNot()) {
                    $resolvedCondition .= ' NOT';
                }

                $resolvedCondition .= " BETWEEN ($resolvedLower AND $resolvedHigher)";
            }
            elseif ($condition instanceof ExistsCondition) {
                $query = $condition->getQuery();
                $resolvedQuery = $this->compileQuery($query);

                $resolvedCondition = $condition->getNot() ? 'NOT EXISTS' : 'EXISTS';
                $resolvedCondition .= "($resolvedQuery)";
            }
            elseif ($condition instanceof NullCondition) {
                $column = $condition->getColumn();

                $resolvedCondition = $expressionResolver($column);
                $resolvedCondition .= $condition->getNot() ? ' IS NOT NULL' : ' IS NULL';
            }
            elseif ($condition instanceof LikeCondition) {
                $column = $condition->getColumn();
                $resolvedColumn = $expressionResolver($column);

                $value = $condition->getValue();
                $escape = $condition->getEscapeCharacter();

                $operator = $condition->getNot() ? 'NOT LIKE' : 'LIKE';
                if ($condition->isCaseSensitive()) {
                    $operator .= ' BINARY';
                }

                if($condition->getType() != LikeType::Like) {
                    $esc = $escape ?? '\\';
                    $value = str_replace(
                        [$esc, '%', '_'],
                        [$esc . $esc, $esc . '%', $esc . '_'],
                        $value
                    );
                }

                switch ($condition->getType()) {
                    case LikeType::Contains:
                        $value = "%{$value}%";
                        break;
                    case LikeType::Starts:
                        $value = "{$value}%";
                        break;
                    case LikeType::Ends:
                        $value = "%{$value}";
                        break;
                }

                $extraEscape = $condition->getType() != LikeType::Like && (empty($escape) || $escape == '\\');
                $value = $this->quote($value, $extraEscape);

                $resolvedCondition = "$resolvedColumn $operator $value";
                if ($escape) {
                    $resolvedCondition .= " ESCAPE " . $this->quote($escape);
                }
            }
            elseif ($condition instanceof InCondition) {
                $column = $condition->getColumn();
                $values = $condition->getValues();

                $resolvedColumn = $expressionResolver($column);
                $resolvedValues = '';
                if ($values instanceof Query) {
                    $resolvedValues = $this->compileQuery($values);
                } else {
                    $first = true;
                    foreach ($values as $value) {
                        if (!$first) $resolvedValues .= ', ';
                        $resolvedValues .= $expressionResolver($value);
                        $first = false;
                    }
                }

                $resolvedCondition = $resolvedColumn;
                $resolvedCondition .= $condition->getNot() ? ' NOT IN ' : ' IN ';
                $resolvedCondition .= "($resolvedValues)";
            }
            elseif ($condition instanceof GroupCondition) {
                /** @var list<AbstractCondition> $clauses */
                $clauses = $condition->getCondition()->getComponents($type);
                $resolvedCondition = $this->compileConditions($clauses);
                if(count($clauses) > 1 || (count($clauses) == 1 && $clauses[0] instanceof RawCondition)) {
                    $resolvedCondition = "($resolvedCondition)";
                }
            }
            elseif ($condition instanceof RawCondition) {
                $resolvedCondition = $this->compileRaw(
                    $condition->getExpression(),
                    $condition->getBindings()
                );
            }

            if ($resolvedCondition) {
                if ($index > 0) $result .= $condition->getOr() ? ' OR ' : ' AND ';
                $result .= $resolvedCondition;
            }

            $index++;
        }

        return $result;
    }

    /**
     * @param list<AbstractCondition> $conditions
     * @return string
     */
    protected function compileWhere(iterable $conditions): string
    {
        $resolvedCondition = $this->compileConditions($conditions);
        if (!empty($resolvedCondition)) {
            return 'WHERE ' . $resolvedCondition;
        }

        return '';
    }

    /**
     * @param list<AbstractColumn> $columns
     * @return string
     */
    protected function compileGroupBy(iterable $columns): string
    {
        $result = $this->compileColumns($columns, false);
        if (empty($result)) {
            return '';
        }

        return 'GROUP BY ' . $result;
    }

    /**
     * @param list<AbstractCondition> $conditions
     * @return string
     */
    protected function compileHaving(iterable $conditions): string
    {
        $resolvedCondition = $this->compileConditions($conditions, ComponentType::Having);
        if (!empty($resolvedCondition)) {
            return 'HAVING ' . $resolvedCondition;
        }

        return '';
    }

    /**
     * @param list<AbstractOrder> $columns
     * @return string
     */
    protected function compileOrderBy($columns): string
    {
        $expressionResolver = function ($expression) {
            if ($expression instanceof AbstractExpression) {
                return $this->compileExpression($expression, false);
            } elseif ($expression instanceof Query) {
                $resolvedValue = $this->compileQuery($expression);
                $resolvedValue = "($resolvedValue)";

                return $resolvedValue;
            }
        };

        $result = '';
        $index = 0;
        foreach ($columns as $column) {
            $resolvedColumn = null;
            if ($column instanceof OrderClause) {

                $columnContent = $column->getColumn();
                $isAscending = $column->isAscending();
                $resolvedColumn = $expressionResolver($columnContent);
                $resolvedColumn .= $isAscending ? ' ASC' : ' DESC';
            }
            elseif ($column instanceof RawOrder) {

                $resolvedColumn = $this->compileRaw(
                    $column->getExpression(),
                    $column->getBindings()
                );
            }
            elseif ($column instanceof RandomOrder) {
                $resolvedColumn = 'RAND()';
            }

            if (!is_null($resolvedColumn)) {
                if ($index > 0) $result .= ', ';
                $result .= $resolvedColumn;
            }

            $index++;
        }

        if (empty($result)) {
            return '';
        }

        return 'ORDER BY ' . $result;
    }

    /**
     * @param ?LimitClause $limitClause
     * @param ?OffsetClause $offsetClause
     * @return string
     */
    protected function compilePaging(?LimitClause $limitClause, ?OffsetClause $offsetClause): string
    {
        $resolvedPaging = '';
        if ($limitClause && $limitClause->hasLimit()) {
            $limit = $limitClause->getLimit();
            if ($offsetClause && $offsetClause->hasOffset()) {
                $offset = $offsetClause->getOffset();
                $resolvedPaging = "LIMIT {$offset}, {$limit}";
            }
            else $resolvedPaging = "LIMIT {$limit}";
        }
        elseif ($offsetClause && $offsetClause->hasOffset()) {
            $offset = $offsetClause->getOffset();
            $resolvedPaging = "LIMIT {$offset}, " . $this->maxValue;
        }

        return $resolvedPaging;
    }

    /**
     * @param list<CombineClause> $combines
     * @return string
     */
    protected function compileCombine(iterable $combines): string
    {
        $result = '';
        $index = 0;
        foreach($combines as $combine) {
            if($index > 0) $result .= ' ';

            $result .= $combine->getOperation()->syntaxOf();
            if($combine->isAll()) {
                $result .= ' ALL';
            }

            $result .= ' ' . $this->compileQuery($combine->getQuery());
            $index++;
        }

        return $result;
    }

    /**
     * @param list<AbstractFrom> $tables
     * @return string
     */
    protected function compileCte(iterable $tables): string
    {
        $result = '';
        
        $index = 0;
        foreach($tables as $fromClause) {
            if($index > 0) $result .= ', ';
            else $result .= 'WITH ';

            $result .= $fromClause->getAlias();

            if($fromClause instanceof FromClause) {
                $query = $fromClause->getTable();
                $result .= ' AS (' . $this->compileQuery($query) . ')';
            }

            $index++;
        }

        return $result;
    }
}