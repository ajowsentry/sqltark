<?php

declare(strict_types=1);

namespace SqlTark\Component;

abstract class AbstractFrom extends AbstractComponent
{
    /**
     * @var ?string $alias
     */
    protected $alias;

    /**
     * @return ?string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param ?string $value
     * @return static
     */
    public function setAlias(?string $value): static
    {
        $this->alias = $value;
        return $this;
    }
}
