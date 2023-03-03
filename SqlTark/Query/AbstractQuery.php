<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Query;
use InvalidArgumentException;
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
            $class = get_class($value);
            throw new InvalidArgumentException("Cannot set the same {$class} as a parent of itself");
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
        $this->components = null;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComponents(?ComponentType $componentType = null): array
    {
        if(is_null($this->components)) {
            return [];
        }

        if(is_null($componentType)) {
            $result = [];
            foreach($this->components as $components) {
                array_push($result, ...$components);
            }
            return $result;
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
    public function getOneComponent(ComponentType $componentType): ?AbstractComponent
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
    public function when(bool $condition, ?callable $whenTrue, ?callable $whenFalse): static
    {
        if($condition && !is_null($whenTrue)) {
            $whenTrue($this);
        }
        elseif(!$condition && !is_null($whenFalse)) {
            $whenFalse($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function whenTrue(bool $condition, ?callable $whenTrue): static
    {
        if($condition && !is_null($whenTrue)) {
            $whenTrue($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function whenFalse(bool $condition, ?callable $whenFalse): static
    {
        if(!$condition && !is_null($whenFalse)) {
            $whenFalse($this);
        }

        return $this;
    }

    public final function __construct() { }
}