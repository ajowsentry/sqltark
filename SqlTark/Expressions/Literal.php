<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

use DateTimeInterface;

final class Literal extends AbstractExpression
{
    /**
     * @var null|scalar|DateTimeInterface $value
     */
    private mixed $value = null;
    
    /**
     * @var ?string $alias
     */
    private ?string $alias = null;

    /**
     * @return null|scalar|DateTimeInterface $value
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param null|scalar|DateTimeInterface $value
     * @return void
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
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
     * @param null|scalar|DateTimeInterface $value
     * @param ?string $alias
     */
    public function __construct(mixed $value, ?string $alias = null)
    {
        $this->value = $value;
        $this->alias = $alias;
    }
}