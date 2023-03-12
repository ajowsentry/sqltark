<?php

declare(strict_types=1);

namespace SqlTark\Query;

use Closure;
use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AbstractComponent;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * @var ?AbstractQuery $parent
     */
    protected ?AbstractQuery $parent = null;
    
    /**
     * @var ?array<int,AbstractComponent[]> $components
     */
    protected ?array $components = null;

    /**
     * @var MethodType $method
     */
    protected MethodType $method = MethodType::Select;

    /**
     * @var ComponentType $conditionComponent
     */
    protected ComponentType $conditionComponent = ComponentType::Where;

    public function getConditionComponent(): ComponentType
    {
        return $this->conditionComponent;
    }

    /**
     * @return ?AbstractQuery
     */
    public function getParent(): ?AbstractQuery
    {
        return $this->parent;
    }

    /**
     * @param AbstractQuery $value
     * @return static
     */
    public function setParent(AbstractQuery $value): static
    {
        if ($this === $value) {
            Helper::throwInvalidArgumentException("Cannot set the same %s as a parent of itself", $value);
        }

        $this->parent = $value;
        return $this;
    }

    /**
     * @return MethodType
     */
    public function getMethod(): MethodType
    {
        return $this->method;
    }

    /**
     * @param MethodType $value
     * @return static
     */
    public function setMethod(MethodType $value): static
    {
        $this->method = $value;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function newQuery(): Query
    {
        return new Query;
    }
    
    /**
     * {@inheritdoc}
     */
    public function newChild(): Query
    {
        return $this->newQuery()->setParent($this);
    }

    /**
     * {@inheritdoc}
     */
    public function addComponent(ComponentType $componentType, AbstractComponent $component): static
    {
        if(is_null($this->components)) {
            $this->components = [];
        }

        $key = $componentType->value;
        if(!isset($this->components[$key])) {
            $this->components[$key] = [];
        }

        $component->setComponentType($componentType);
        array_push($this->components[$key], $component);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOrReplaceComponent(ComponentType $componentType, AbstractComponent $component): static
    {
        if(is_null($this->components)) {
            $this->components = [];
        }

        $key = $componentType->value;
        if(isset($this->components[$key])) {
            unset($this->components[$key]);
        }

        return $this->addComponent($componentType, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function clearComponents(?ComponentType $componentType = null): static
    {
        if(is_null($componentType)) {
            $this->components = null;
        }

        elseif($this->hasComponent($componentType)) {
            unset($this->components[$componentType->value]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComponents(?ComponentType $componentType = null, string $expectClass = AbstractComponent::class): array
    {
        if(is_null($this->components)) {
            return [];
        }

        if(is_null($componentType)) {
            return Helper::flatten($this->components);
        }

        $key = $componentType->value;
        if(!isset($this->components[$key])) {
            return [];
        }

        return $this->components[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function getOneComponent(ComponentType $componentType, string $expectClass = AbstractComponent::class): ?AbstractComponent
    {
        return $this->hasComponent($componentType)
            ? $this->components[$componentType->value][0]
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasComponent(ComponentType $componentType): bool
    {
        return isset($this->components, $this->components[$componentType->value]);
    }

    /**
     * {@inheritdoc}
     */
    public function when(bool $condition, Closure $whenTrue, ?Closure $whenFalse = null): static
    {
        if($condition) {
            $whenTrue($this);
        }

        elseif(!is_null($whenFalse)) {
            $whenFalse($this);
        }

        return $this;
    }

    public final function __construct() { }
}