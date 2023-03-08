<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

use DateTimeInterface;

final class Literal extends AbstractExpression
{
    /**
     * @var null|scalar|DateTimeInterface $value
     */
    private mixed $value;

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
     * @param null|scalar|DateTimeInterface $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }
}