<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

class Literal extends BaseExpression
{
    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return static
     */
    public function setValue($value): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }
}
