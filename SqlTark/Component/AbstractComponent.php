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
     * @return void
     */
    public function setComponentType(ComponentType $componentType): void
    {
        $this->componentType = $componentType;
    }

    public final function __construct() { }
}