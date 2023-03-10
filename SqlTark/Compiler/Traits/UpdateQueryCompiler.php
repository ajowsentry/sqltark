<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Component\AbstractCondition;
use SqlTark\Component\AbstractFrom;
use SqlTark\Component\AbstractJoin;
use SqlTark\Component\AbstractOrder;
use SqlTark\Component\UpdateClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\LimitClause;
use SqlTark\Component\OffsetClause;
use SqlTark\Expressions\AbstractExpression;

trait UpdateQueryCompiler
{
    use ExpressionCompiler, SelectQueryCompiler;

    /**
     * {@inheritdoc}
     */
    public function compileUpdateQuery(Query $query): string
    {
        $from = $query->getOneComponent(ComponentType::From, AbstractFrom::class);
        $joins = $query->getComponents(ComponentType::Join, AbstractJoin::class);
        $where = $query->getComponents(ComponentType::Where, AbstractCondition::class);
        $orderBy = $query->getComponents(ComponentType::OrderBy, AbstractOrder::class);
        $limit = $query->getOneComponent(ComponentType::Limit, LimitClause::class);
        $offset = $query->getOneComponent(ComponentType::Offset, OffsetClause::class);
        $update = $query->getOneComponent(ComponentType::Update, UpdateClause::class);

        if(empty($from)) {
            throw new InvalidArgumentException("Table not specified!");
        }

        if(empty($update)) {
            throw new InvalidArgumentException("Update value not specified!");
        }

        $result = 'UPDATE ' . $this->compileFrom($from);

        $resolvedJoin = $this->compileJoin($joins);
        if($resolvedJoin) {
            $result .= ' ' . $resolvedJoin;
        }

        $expressionResolver = function ($expression) {

            if ($expression instanceof AbstractExpression) {
                return $this->compileExpression($expression, false);
            } elseif ($expression instanceof Query) {
                $resolvedValue = $this->compileQuery($expression);
                $resolvedValue = "($resolvedValue)";
                return $resolvedValue;
            }
        };

        if($update instanceof UpdateClause) {
            $result .= ' SET ';
            $first = true;
            foreach($update->getValues() as $column => $value) {
                if(!$first) $result .= ', ';
                $result .= $this->wrapIdentifier($column);
                $result .= ' = ';
                $result .= $expressionResolver($value);
                $first = false;
            }
        }

        $resolvedWhere = $this->compileWhere($where);
        if($resolvedWhere) {
            $result .= ' ' . $resolvedWhere;
        }

        $resolvedOrderBy = $this->compileOrderBy($orderBy);
        if($resolvedOrderBy) {
            $result .= ' ' . $resolvedOrderBy;
        }

        $resolvedPaging = $this->compilePaging($limit, $offset);
        if($resolvedPaging) {
            $result .= ' ' . $resolvedPaging;
        }

        return $result;
    }
}