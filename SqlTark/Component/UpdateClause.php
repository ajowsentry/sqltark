<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Expressions\BaseExpression;
use SqlTark\Helper;
use SqlTark\Query;

class UpdateClause extends AbstractFrom
{
    /**
     * @var array<string,BaseExpression|Query> $values
     */
    protected $value;

    /**
     * @return array<string,BaseExpression|Query>
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @param array<string,BaseExpression|Query> $value
     * @return void
     */
    public function setValue(array $value): void
    {
        $this->value = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->value = Helper::cloneObject($this->value);
    }
}
