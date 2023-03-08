<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use SqlTark\Query;
use DateTimeInterface;
use InvalidArgumentException;
use SqlTark\Utilities\Helper;
use SqlTark\Component\RawFrom;
use SqlTark\Component\FromClause;
use SqlTark\Component\ComponentType;
use SqlTark\Expressions\AbstractExpression;
use SqlTark\Component\AdHocTableFromClause;

trait From
{
    use BaseFrom;

    /**
     * @param Query|(Closure(Query):void) $table
     * @param string $alias
     * @return static Self object
     */
    public function fromQuery(Query|Closure $table, ?string $alias = null): static
    {
        $table = Helper::resolveQuery($table, $this);
        if (empty($alias ?: $table->getAlias())) {
            throw new InvalidArgumentException(
                "No Alias found for sub query from"
            );
        }

        $component = new FromClause;
        $component->setTable($table);
        $component->setAlias($alias);

        return $this->addOrReplaceComponent(ComponentType::From, $component);
    }

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression ...$bindings
     * @return static Self object
     */
    public function fromRaw(string $expression, ...$bindings): static
    {
        $component = new RawFrom;
        $component->setExpression($expression);
        $component->setBindings(Helper::resolveExpressionList($bindings));

        return $this->addOrReplaceComponent(ComponentType::From, $component);
    }
}
