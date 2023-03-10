<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Component\AbstractCondition;
use SqlTark\Component\AbstractFrom;
use SqlTark\Component\AbstractJoin;
use SqlTark\Component\AbstractOrder;
use SqlTark\Component\ComponentType;
use SqlTark\Component\LimitClause;
use SqlTark\Component\OffsetClause;

trait DeleteQueryCompiler
{
    use ExpressionCompiler, SelectQueryCompiler;

    /**
     * {@inheritdoc}
     */
    public function compileDeleteQuery(Query $query): string
    {
        $from = $query->getOneComponent(ComponentType::From, AbstractFrom::class);
        $joins = $query->getComponents(ComponentType::Join, AbstractJoin::class);
        $where = $query->getComponents(ComponentType::Where, AbstractCondition::class);
        $orderBy = $query->getComponents(ComponentType::OrderBy, AbstractOrder::class);
        $limit = $query->getOneComponent(ComponentType::Limit, LimitClause::class);
        $offset = $query->getOneComponent(ComponentType::Offset, OffsetClause::class);

        if(empty($from)) {
            throw new InvalidArgumentException("Table not specified!");
        }

        $result = 'DELETE FROM ' . $this->compileFrom($from);

        $resolvedJoin = $this->compileJoin($joins);
        if($resolvedJoin) {
            $result .= ' ' . $resolvedJoin;
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