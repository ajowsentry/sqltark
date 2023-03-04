<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;

class InsertQueryClause extends AbstractInsert
{
    /**
     * @var Query $query
     */
    protected Query $query;

    /**
     * @var ?list<string> $columns
     */
    protected ?iterable $columns;

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
     * @return ?list<string>
     */
    public function getColumns(): ?iterable
    {
        return $this->columns;
    }

    /**
     * @param ?list<string> $value
     * @return void
     */
    public function setColumns(?iterable $value): void
    {
        $this->columns = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->query = Helper::cloneObject($this->query);
        $this->columns = Helper::cloneObject($this->columns);
    }
}
