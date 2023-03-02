<?php

declare(strict_types=1);

namespace SqlTark\Component;

abstract class AbstractComponent
{
    /**
     * @var ComponentType $componentType
     */
    protected ComponentType $componentType;

    /**
     * @return ComponentType
     */
    public function getComponentType(): ComponentType
    {
        return $this->componentType;
    }

    /**
     * @param ComponentType $componentType
     * @return static
     */
    public function setComponentType(ComponentType $componentType): static
    {
        $this->componentType = $componentType;
        return $this;
    }

    public final function __construct() { }
}