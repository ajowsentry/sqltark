<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;

class FromClause extends AbstractFrom
{
    /**
     * @var string|Query $table
     */
    protected string|Query $table;

    /**
     * @return string|Query
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string|Query $value
     * @return void
     */
    public function setTable(string|Query $value): void
    {
        $this->table = $value;
    }

    public function getAlias(): string
    {
        if(empty($this->alias))
        {
            if(is_string($this->table))
            {
                if (stripos($this->table, ' as ') !== false) {
                    $segments = array_filter(explode(' ', $this->table), function ($item) {
                        return $item != '';
                    });

                    return $segments[2];
                }
            }
            elseif($this->table instanceof Query)
            {
                // return $this->table->getAlias();
            }
        }

        return $this->alias;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->table = Helper::cloneObject($this->table);
    }
}
