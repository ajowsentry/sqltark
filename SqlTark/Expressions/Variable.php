<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

final class Variable extends AbstractExpression
{
    /**
     * @var ?string $name
     */
    protected ?string $name = null;
    
    /**
     * @var ?string $alias
     */
    private ?string $alias = null;

    /**
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param ?string $value
     * @return void
     */
    public function setName(?string $value): void
    {
        $this->name = $value;
    }

    /**
     * @return ?string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param ?string $value
     * @return void
     */
    public function setAlias(?string $value): void
    {
        $this->alias = $value;
    }

    /**
     * @param string $value
     * @return static
     */
    public function as(string $value): static
    {
        $this->alias = $value;
        return $this;
    }

    /**
     * @param ?string $name
     * @param ?string $alias
     */
    public function __construct(?string $name = null, ?string $alias = null)
    {
        $this->name = $name;
        $this->alias = $alias;
    }
}
