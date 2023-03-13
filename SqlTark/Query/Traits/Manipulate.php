<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Component\InsertClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\InsertQueryClause;
use SqlTark\Component\UpdateClause;
use SqlTark\Expressions\AbstractExpression;

trait Manipulate
{
    /**
     * @return static
     */
    public function asDelete(): static
    {
        $this->method = MethodType::Delete;
        return $this;
    }

    /**
     * @param array<string,null|scalar|DateTimeInterface|AbstractExpression|Query> $keyValues
     * @return static
     */
    public function asInsert(array $keyValues): static
    {
        $this->method = MethodType::Insert;

        $component = new InsertClause;
        $component->setColumns(array_keys($keyValues));
        $component->setValues([Helper::resolveExpressionList(array_values($keyValues), false)]);

        return $this->addOrReplaceComponent(ComponentType::Insert, $component);
    }

    /**
     * @param list<string> $columns
     * @param list<list<AbstractExpression|Query>> $values
     * @return static
     */
    public function asBulkInsert(iterable $columns, iterable $values): static
    {
        $this->method = MethodType::Insert;

        $component = new InsertClause;
        $component->setColumns($columns);
        $component->setValues(array_map(fn($item) => Helper::resolveExpressionList($item), $values));

        return $this->addOrReplaceComponent(ComponentType::Insert, $component);
    }

    /**
     * @param (Closure(Query):void)|Query $query
     * @param ?list<string> $columns
     * @return static
     */
    public function asInsertWith(Closure|Query $query, ?iterable $columns = null): static
    {
        $this->method = MethodType::Insert;
        
        $component = new InsertQueryClause;
        $component->setQuery($query);
        $component->setColumns($columns);

        return $this->addOrReplaceComponent(ComponentType::Insert, $component);
    }

    /**
     * @param array<string,null|scalar|DateTimeInterface|AbstractExpression|Query> $keyValues
     * @return static
     */
    public function asUpdate(array $keyValues): static
    {
        $this->method = MethodType::Update;

        $component = new UpdateClause;
        $component->setValues(Helper::resolveExpressionList($keyValues, false));

        return $this->addOrReplaceComponent(ComponentType::Update, $component);
    }
}