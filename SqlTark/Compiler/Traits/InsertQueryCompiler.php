<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Utilities\Helper;
use SqlTark\Component\RawFrom;
use SqlTark\Component\FromClause;
use SqlTark\Component\InsertClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\InsertQueryClause;
use SqlTark\Expressions\AbstractExpression;

trait InsertQueryCompiler
{
    use ExpressionCompiler, SelectQueryCompiler;

    /**
     * {@inheritdoc}
     */
    public function compileInsertQuery(Query $query): string
    {
        $from = $query->getOneComponent(ComponentType::From);
        if(empty($from)) {
            throw new InvalidArgumentException(
                "Insert query does not have table reference"
            );
        }

        $values = $query->getOneComponent(ComponentType::Insert);
        if(empty($values)) {
            throw new InvalidArgumentException(
                "Insert query does not have value"
            );
        }

        $resolvedTable = null;
        if($from instanceof FromClause) {
            $table = $from->getTable();
            $resolvedTable = $this->wrapIdentifier($table);
        }
        elseif($from instanceof RawFrom) {
            $expression = $from->getExpression();
            $bindings = $from->getBindings();
            $resolvedTable = $this->compileRaw($expression, $bindings);
        }
        else {
            $class = Helper::getType($from);
            throw new InvalidArgumentException(
                "Could not resolve '$class' for insert query"
            );
        }

        $result = "INSERT INTO $resolvedTable ";
        if($values instanceof InsertClause) {
            $result .= '(';
            $index = 0;
            foreach($values->getColumns() as $column) {
                if($index > 0) {
                    $result .= ', ';
                }

                $result .= $this->wrapIdentifier($column);
                $index++;
            }
            $result .= ') VALUES ';

            $first = true;
            foreach ($values->getValues() as $row) {
                if (!$first) {
                    $result .= ', ';
                }

                $result .= '(';
                $index = 0;
                foreach ($row as $value) {
                    $resolvedValue = null;
                    if ($value instanceof AbstractExpression) {
                        $resolvedValue = $this->compileExpression($value, false);
                    } elseif ($value instanceof Query) {
                        $resolvedValue = '(' . $this->compileQuery($value) . ')';
                    }

                    if ($index > 0) {
                        $result .= ', ';
                    }

                    $result .= $resolvedValue;
                    $index++;
                }
                $result .= ')';

                $first = false;
            }
        }
        elseif($values instanceof InsertQueryClause) {
            $columns = $values->getColumns();
            if(!empty($columns)) {
                $result .= '(';
                $index = 0;
                foreach($columns as $column) {
                    if($index > 0) {
                        $result .= ', ';
                    }

                    $result .= $this->wrapIdentifier($column);
                    $index++;
                }
                $result .= ') ';
            }

            $query = $values->getQuery();
            $result .= $this->compileQuery($query);
        }

        return $result;
    }
}