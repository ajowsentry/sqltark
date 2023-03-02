<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

class Variable extends BaseExpression
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setName(string $value): static
    {
        $this->name = $value;
        return $this;
    }

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
