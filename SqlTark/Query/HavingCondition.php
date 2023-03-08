<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Component\ComponentType;

class HavingCondition extends AbstractQuery implements ConditionInterface
{
    /**
     * @var ComponentType $conditionComponent
     */
    protected ComponentType $conditionComponent = ComponentType::Having;

    use Traits\Condition;
}