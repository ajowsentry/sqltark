<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Helper;
use InvalidArgumentException;
use SqlTark\Component\FromClause;
use SqlTark\Component\ComponentType;

trait BaseFrom
{
    /**
     * @var ?string $alias
     */
    protected ?string $alias;

    /**
     * @return ?string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param ?string $alias
     * @return static
     */
    public function setAlias(?string $value): static
    {
        $this->alias = $value;
        return $this;
    }

    /**
     * @param ?string $alias
     * @return $this Self object
     */
    public function alias(?string $alias)
    {
        return $this->setAlias($alias);
    }

    /**
     * @return $this Self object
     */
    public function from($table, ?string $alias = null)
    {
        $component = null;
        if (is_string($table)) {
            $component = new FromClause;
            $component->setTable($table);
            $component->setAlias($alias);
        } else {
            $class = Helper::getType($table);
            throw new InvalidArgumentException("Could not resolve '{$class}' as table");
        }

        return $this->addOrReplaceComponent(ComponentType::From, $component);
    }
}