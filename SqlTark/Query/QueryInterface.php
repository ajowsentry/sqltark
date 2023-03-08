<?php

declare(strict_types=1);

namespace SqlTark\Query;

use Closure;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AbstractComponent;
use SqlTark\Query;

interface QueryInterface
{
    /**
     * Get query parent
     * @return ?AbstractQuery Parent
     */
    public function getParent(): ?AbstractQuery;

    /**
     * Set query parent
     * @param AbstractQuery $value Parent
     */
    public function setParent(AbstractQuery $value): static;

    /**
     * Create new ```Query``` object
     * @return Query
     */
    public function newQuery(): Query;

    /**
     * Create new ```Query``` object then set parent as ```this``` object
     * @return Query
     */
    public function newChild(): Query;

    /**
     * Add new component
     * 
     * @param ComponentType $componentType Component type from ```ComponentType``` enum class
     * @param AbstractComponent $component Component object
     * @return static Self object
     */
    public function addComponent(ComponentType $componentType, AbstractComponent $component): static;

    /**
     * Remove old component with specified ```componentType``` if exists then
     * add new component.
     * 
     * @param ComponentType $componentType Component type from ```ComponentType``` enum class
     * @param AbstractComponent $component Component object
     * @return static Self object
     */
    public function addOrReplaceComponent(ComponentType $componentType, AbstractComponent $component): static;

    /**
     * Remove all component with specified ```componentType``` if exists
     * 
     * @param ?ComponentType $componentType Component type from ```ComponentType``` enum class
     * @return static Self object
     */
    public function clearComponents(?ComponentType $componentType = null): static;

    /**
     * Get all components with specified ```componentType```.
     * 
     * Get all components when no parameter is specified.
     * 
     * @param ?ComponentType $componentType Component type from ```ComponentType``` enum class
     * @return AbstractComponent[] Components.
     */
    public function getComponents(?ComponentType $componentType = null): array;

    /**
     * Get single components with specified ```componentType```.
     * 
     * @param ComponentType $componentType Component type from ```ComponentType``` enum class
     * @return ?AbstractComponent Component.
     */
    public function getOneComponent(ComponentType $componentType): ?AbstractComponent;

    /**
     * Check wether components with specified ```componentType``` is exists.
     * 
     * @param ComponentType $componentType Component type from ```ComponentType``` enum class
     * @return bool Is exists.
     */
    public function hasComponent(ComponentType $componentType): bool;

    /**
     * Execute query when condition is fulfilled
     * 
     * @param bool $condition Condition
     * @param (Closure(QueryInterface):void) $whenTrue Query when true
     * @param ?(Closure(QueryInterface):void) $whenFalse Query when false
     * @return static Self object
     */
    public function when(bool $condition, Closure $whenTrue, ?Closure $whenFalse): static;
}