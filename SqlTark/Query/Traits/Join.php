<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use DateTimeInterface;
use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Component\JoinType;
use SqlTark\Component\JoinClause;
use SqlTark\Component\ComponentType;
use SqlTark\Query\Join as QueryJoin;
use SqlTark\Expressions\AbstractExpression;

trait Join
{
    /**
     * @param (Closure(QueryJoin):void)|QueryJoin $join
     * @param ?JoinType $type
     * @return static
     */
    public function joinWith(Closure|QueryJoin $join, ?JoinType $type = null): static
    {
        $join = Helper::resolveJoin($join, $this);

        if(! is_null($type))
            $join->asType($type);
        
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @param JoinType $type
     * @return static
     */
    public function join(string|Closure|Query $table, mixed $left, string $operator, mixed $right, JoinType $type = JoinType::Join): static
    {
        $join = new QueryJoin;
        is_string($table) ? $join->from($table) : $join->fromQuery($table);

        $join->asType($type)->on($left, $operator, $right);
        
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static
     */
    public function innerJoin(string|Closure|Query $table, mixed $left, string $operator, mixed $right): static
    {
        return $this->join($table, $left, $operator, $right, JoinType::InnerJoin);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static
     */
    public function leftJoin(string|Closure|Query $table, mixed $left, string $operator, mixed $right): static
    {
        return $this->join($table, $left, $operator, $right, JoinType::LeftJoin);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static
     */
    public function rightJoin(string|Closure|Query $table, mixed $left, string $operator, mixed $right): static
    {
        return $this->join($table, $left, $operator, $right, JoinType::RightJoin);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static
     */
    public function outerJoin(string|Closure|Query $table, mixed $left, string $operator, mixed $right): static
    {
        return $this->join($table, $left, $operator, $right, JoinType::OuterJoin);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static
     */
    public function leftOuterJoin(string|Closure|Query $table, mixed $left, string $operator, mixed $right): static
    {
        return $this->join($table, $left, $operator, $right, JoinType::LeftOuterJoin);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static
     */
    public function rightOuterJoin(string|Closure|Query $table, mixed $left, string $operator, mixed $right): static
    {
        return $this->join($table, $left, $operator, $right, JoinType::RightOuterJoin);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static
     */
    public function fullOuterJoin(string|Closure|Query $table, mixed $left, string $operator, mixed $right): static
    {
        return $this->join($table, $left, $operator, $right, JoinType::FullOuterJoin);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @return static
     */
    public function crossJoin(string|Closure|Query $table): static
    {
        $join = new QueryJoin;
        is_string($table) ? $join->from($table) : $join->fromQuery($table);

        $join->asType(JoinType::NaturalJoin);
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }

    /**
     * @param string|(Closure(Query):void)|Query $table
     * @return static
     */
    public function naturalJoin(string|Closure|Query $table): static
    {
        $join = new QueryJoin;
        is_string($table) ? $join->from($table) : $join->fromQuery($table);

        $join->asType(JoinType::NaturalJoin);
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }
}