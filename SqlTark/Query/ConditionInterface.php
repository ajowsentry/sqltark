<?php

declare(strict_types=1);

namespace SqlTark\Query;

use Closure;
use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Component\LikeType;
use SqlTark\Expressions\AbstractExpression;

interface ConditionInterface
{
    /**
     * @return static Self object
     */
    public function or(): static;

    /**
     * @return static Self object
     */
    public function and(): static;

    /**
     * @param bool $value
     * @return static Self object
     */
    public function not(bool $value = true): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function compare(mixed $left, string $operator, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orCompare(mixed $left, string $operator, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function equals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function notEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orNotEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function greaterThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orGreaterThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function greaterEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orGreaterEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function lesserThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orLesserThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function lesserEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orLesserEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query> $list
     * @return static Self object
     */
    public function in(mixed $column, iterable $list): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query> $list
     * @return static Self object
     */
    public function orIn(mixed $column, iterable $list): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query> $list
     * @return static Self object
     */
    public function notIn(mixed $column, iterable $list): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query> $list
     * @return static Self object
     */
    public function orNotIn(mixed $column, iterable $list): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function isNull(mixed $column): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function orIsNull(mixed $column): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function notIsNull(mixed $column): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function orNotIsNull(mixed $column): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return static Self object
     */
    public function between(mixed $column, mixed $low, mixed $high): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return static Self object
     */
    public function orBetween(mixed $column, mixed $low, mixed $high): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return static Self object
     */
    public function notBetween(mixed $column, mixed $low, mixed $high): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return static Self object
     */
    public function orNotBetween(mixed $column, mixed $low, mixed $high): static;

    /**
     * @param (Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return static Self object
     */
    public function group(Closure|ConditionInterface $condition): static;

    /**
     * @param (Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return static Self object
     */
    public function orGroup(Closure|ConditionInterface $condition): static;

    /**
     * @param (Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return static Self object
     */
    public function notGroup(Closure|ConditionInterface $condition): static;

    /**
     * @param (Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return static Self object
     */
    public function orNotGroup(Closure|ConditionInterface $condition): static;

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function exists(Closure|Query $query): static;

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function orExists(Closure|Query $query): static;

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function notExists(Closure|Query $query): static;

    /**
     * @param (Closure(Query):void)|Query $query
     * @return static Self object
     */
    public function orNotExists(Closure|Query $query): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function like(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null, LikeType $likeType = LikeType::Like): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function notLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orNotLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function startsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orStartsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function notStartsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orNotStartsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function endsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orEndsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function notEndsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orNotEndsWith(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function contains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function notContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orNotContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return static Self object
     */
    public function conditionRaw(string $expression, mixed ...$bindings): static;

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return static Self object
     */
    public function orConditionRaw(string $expression, mixed ...$bindings): static;

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return static Self object
     */
    public function notConditionRaw(string $expression, mixed ...$bindings): static;

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return static Self object
     */
    public function orNotConditionRaw(string $expression, mixed ...$bindings): static;
}