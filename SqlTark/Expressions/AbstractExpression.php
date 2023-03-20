<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

abstract class AbstractExpression
{
    /**
     * @var ?string $wrap
     */
    protected ?string $wrap = null;

    /**
     * @return ?string
     */
    public function getWrap(): ?string
    {
        return $this->wrap;
    }

    /**
     * @param ?string $value
     * @return static Self object
     */
    public function wrap(?string $value): static
    {
        $this->wrap = $value;
        return $this;
    }
}