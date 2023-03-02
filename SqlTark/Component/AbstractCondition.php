<?php

declare(strict_types=1);

namespace SqlTark\Component;

abstract class AbstractCondition extends AbstractComponent
{
    /**
     * @var bool $isOr
     */
    protected bool $isOr = false;

    /**
     * @var bool $isNot
     */
    protected bool $isNot = false;

    /**
     * @return bool
     */
    public function getOr(): bool
    {
        return $this->isOr;
    }

    /**
     * @param bool $value
     * @return static
     */
    public function setOr(bool $value): static
    {
        $this->isOr = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getNot(): bool
    {
        return $this->isNot;
    }

    /**
     * @param bool $value
     * @return static
     */
    public function setNot(bool $value): static
    {
        $this->isNot = $value;
        return $this;
    }
}
