<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Component\LikeType;
use SqlTark\Component\InCondition;
use SqlTark\Component\RawCondition;
use SqlTark\Component\CompareClause;
use SqlTark\Component\LikeCondition;
use SqlTark\Component\NullCondition;
use SqlTark\Component\GroupCondition;
use SqlTark\Query\ConditionInterface;
use SqlTark\Component\ExistsCondition;
use SqlTark\Component\BetweenCondition;

trait BaseCondition
{
    /**
     * @var bool $orFlag
     */
    protected bool $orFlag = false;

    /**
     * @var bool $notFlag
     */
    protected bool $notFlag = false;

    /**
     * @return static Self object
     */
    public function and(): static
    {
        $this->orFlag = false;
        return $this;
    }

    /**
     * @return static Self object
     */
    public function or(): static
    {
        $this->orFlag = true;
        return $this;
    }

    /**
     * @param bool $value
     * @return static Self object
     */
    public function not(bool $value = true): static
    {
        $this->notFlag = $value;
        return $this;
    }

    /**
     * @return bool
     */
    protected function getOr(): bool
    {
        $return = $this->orFlag;
        $this->orFlag = false;

        return $return;
    }

    /**
     * @return bool
     */
    protected function getNot(): bool
    {
        $return = $this->notFlag;
        $this->notFlag = false;

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function compare(mixed $left, string $operator, mixed $right): static
    {
        $component = new CompareClause;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setLeft(Helper::resolveExpression($left, true));
        $component->setOperator($operator);
        $component->setRight(Helper::resolveExpression($right));

        return $this->addComponent($this->conditionComponent, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function in(mixed $column, iterable $list): static
    {
        $resolvedValues = [];
        foreach($list as $item) {
            array_push($resolvedValues, Helper::resolveExpression($item));
        }

        $component = new InCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));
        $component->setValues(Helper::resolveExpressionList($list));

        return $this->addComponent($this->conditionComponent, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function isNull(mixed $column): static
    {
        $component = new NullCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));

        return $this->addComponent($this->conditionComponent, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function between(mixed $column, mixed $low, mixed $high): static
    {
        $component = new BetweenCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));
        $component->setLower(Helper::resolveExpression($low));
        $component->setHigher(Helper::resolveExpression($high));

        return $this->addComponent($this->conditionComponent, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function group(Closure|ConditionInterface $condition): static
    {
        $component = new GroupCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setCondition(Helper::resolveCondition($condition, $this));

        return $this->addComponent($this->conditionComponent, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Closure|Query $query): static
    {
        $component = new ExistsCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setQuery(Helper::resolveQuery($query, $this));

        return $this->addComponent($this->conditionComponent, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function like(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null, LikeType $likeType = LikeType::Like): static
    {
        $component = new LikeCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));
        $component->setValue($value);
        $component->setCaseSensitive($caseSensitive);
        $component->setEscapeCharacter($escapeCharacter);
        $component->setType($likeType);

        return $this->addComponent($this->conditionComponent, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function conditionRaw(string $expression, mixed ...$bindings): static
    {
        $component = new RawCondition;
        $component->setExpression($expression);
        $component->setBindings(Helper::resolveExpressionList($bindings));

        return $this->addComponent($this->conditionComponent, $component);
    }
}