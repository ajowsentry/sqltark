<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use Closure;
use SqlTark\Query;
use SqlTark\Component\LikeType;
use SqlTark\Query\ConditionInterface;

trait Condition
{
    use BaseCondition;

    /**
     * {@inheritdoc}
     */
    public function orCompare(mixed $left, string $operator, mixed $right): static
    {
        return $this->or()->compare($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(mixed $left, mixed $right): static
    {
        return $this->compare($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orEquals(mixed $left, mixed $right): static
    {
        return $this->or()->compare($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function notEquals(mixed $left, mixed $right): static
    {
        return $this->compare($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotEquals(mixed $left, mixed $right): static
    {
        return $this->or()->compare($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function greaterThan(mixed $left, mixed $right): static
    {
        return $this->compare($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orGreaterThan(mixed $left, mixed $right): static
    {
        return $this->or()->compare($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function greaterEquals(mixed $left, mixed $right): static
    {
        return $this->compare($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orGreaterEquals(mixed $left, mixed $right): static
    {
        return $this->or()->compare($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function lesserThan(mixed $left, mixed $right): static
    {
        return $this->compare($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orLesserThan(mixed $left, mixed $right): static
    {
        return $this->or()->compare($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function lesserEquals(mixed $left, mixed $right): static
    {
        return $this->compare($left, '<=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orLesserEquals(mixed $left, mixed $right): static
    {
        return $this->or()->compare($left, '<=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orIn(mixed $column, iterable|Query $list): static
    {
        return $this->or()->in($column, $list);
    }

    /**
     * {@inheritdoc}
     */
    public function notIn(mixed $column, iterable|Query $list): static
    {
        return $this->not()->in($column, $list);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotIn(mixed $column, iterable|Query $list): static
    {
        return $this->or()->not()->in($column, $list);
    }

    /**
     * {@inheritdoc}
     */
    public function orIsNull(mixed $column): static
    {
        return $this->or()->isNull($column);
    }

    /**
     * {@inheritdoc}
     */
    public function notIsNull(mixed $column): static
    {
        return $this->not()->isNull($column);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotIsNull(mixed $column): static
    {
        return $this->or()->not()->isNull($column);
    }

    /**
     * {@inheritdoc}
     */
    public function orBetween(mixed $column, mixed $low, mixed $high): static
    {
        return $this->or()->between($column, $low, $high);
    }

    /**
     * {@inheritdoc}
     */
    public function notBetween(mixed $column, mixed $low, mixed $high): static
    {
        return $this->not()->between($column, $low, $high);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotBetween(mixed $column, mixed $low, mixed $high): static
    {
        return $this->or()->not()->between($column, $low, $high);
    }

    /**
     * {@inheritdoc}
     */
    public function orGroup(Closure|ConditionInterface $condition): static
    {
        return $this->or()->group($condition);
    }

    /**
     * {@inheritdoc}
     */
    public function notGroup(Closure|ConditionInterface $condition): static
    {
        return $this->not()->group($condition);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotGroup(Closure|ConditionInterface $condition): static
    {
        return $this->or()->not()->group($condition);
    }

    /**
     * {@inheritdoc}
     */
    public function orExists(Closure|Query $query): static
    {
        return $this->or()->exists($query);
    }

    /**
     * {@inheritdoc}
     */
    public function notExists(Closure|Query $query): static
    {
        return $this->not()->exists($query);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotExists(Closure|Query $query): static
    {
        return $this->or()->not()->exists($query);
    }

    /**
     * {@inheritdoc}
     */
    public function orLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->like($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->not()->like($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->not()->like($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function startsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->like($column, $value, $caseSensitive, $escapeCharacter, LikeType::Starts);
    }

    /**
     * {@inheritdoc}
     */
    public function orStartsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->startsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notStartsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->not()->startsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotStartsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->not()->startsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function endsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->like($column, $value, $caseSensitive, $escapeCharacter, LikeType::Ends);
    }

    /**
     * {@inheritdoc}
     */
    public function orEndsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->endsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notEndsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->not()->endsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotEndsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->not()->endsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function contains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->like($column, $value, $caseSensitive, $escapeCharacter, LikeType::Contains);
    }

    /**
     * {@inheritdoc}
     */
    public function orContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->contains($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->not()->contains($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static
    {
        return $this->or()->not()->contains($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orConditionRaw(string $expression, mixed ...$bindings): static
    {
        return $this->or()->conditionRaw($expression, ...$bindings);
    }

    /**
     * {@inheritdoc}
     */
    public function notConditionRaw(string $expression, mixed ...$bindings): static
    {
        return $this->not()->conditionRaw($expression, ...$bindings);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotConditionRaw(string $expression, mixed ...$bindings): static
    {
        return $this->or()->not()->conditionRaw($expression, ...$bindings);
    }
}