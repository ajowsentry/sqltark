<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

abstract class BaseExpression
{
    /**
     * @var ?string $wrap
     */
    protected ?string $wrap;

    /**
     * @return ?string
     */
    public function getWrap(): ?string
    {
        return $this->wrap;
    }

    /**
     * @return static Self object
     */
    public function wrap(?string $value): static
    {
        $this->wrap = $value;
        return $this;
    }
}
