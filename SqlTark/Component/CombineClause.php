<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;

class CombineClause extends AbstractComponent
{
    /**
     * @var Query $query
     */
    protected Query $query;

    /**
     * @var CombineType $operation
     */
    protected CombineType $operation;

    /**
     * @var bool $all
     */
    protected bool $all = false;

    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param Query $value
     * @return void
     */
    public function setQuery(Query $value): void
    {
        $this->query = $value;
    }

    /**
     * @return CombineType
     */
    public function getOperation(): CombineType
    {
        return $this->operation;
    }

    /**
     * @param CombineType $value
     * @return void
     */
    public function setOperation(CombineType $value): void
    {
        $this->operation = $value;
    }

    /**
     * @return bool
     */
    public function isAll(): bool
    {
        return $this->all;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setAll(bool $value): void
    {
        $this->all = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->query = Helper::clone($this->query);
    }
}
