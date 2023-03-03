<?php

declare(strict_types=1);

namespace SqlTark\Query;

use Closure;
use SqlTark\Query;
use SqlTark\Expressions\BaseExpression;

/**
 * @method static having(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * @method static orHaving(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * @method static havingNot(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * @method static orHavingNot(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * 
 * @method static having(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 * @method static orHaving(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 * @method static havingNot(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 * @method static orHavingNot(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 */
interface HavingInterface extends QueryInterface
{
    /**
     * Where and condtition
     * @return static Self object
     */
    public function havingAnd(): static;

    /**
     * Where or condtition
     * @return static Self object
     */
    public function havingOr(): static;

    /**
     * Where not condtition
     * @param bool $value Is not?
     * @return static Self object
     */
    public function notHaving(bool $value = true): static;

    /**
     * Where compare two value using ```and``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function having(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where compare two value using ```or``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function orHaving(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where compare two value using ```not``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function havingNot(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where compare two value ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function orHavingNot(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where value is in values using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function havingIn(mixed $column, mixed $values): static;

    /**
     * Where value is in values using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function orHavingIn(mixed $column, mixed $values): static;

    /**
     * Where value is in values using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function havingNotIn(mixed $column, mixed $values): static;

    /**
     * Where value is in values using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function orHavingNotIn(mixed $column, mixed $values): static;

    /**
     * Where raw expression using ```and``` clause
     * @param string $expression
     * @param null|scalar|BaseExpression|Query ...$bindings
     * @return static Self object
     */
    public function havingRaw(string $expression, mixed ...$bindings): static;

    /**
     * Where raw expression using ```or``` clause
     * @param string $expression
     * @param null|scalar|BaseExpression|Query ...$bindings
     * @return static Self object
     */
    public function orHavingRaw(string $expression, mixed ...$bindings): static;

    /**
     * Where value is null using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function havingNull(mixed $column): static;

    /**
     * Where value is null using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function orHavingNull(mixed $column): static;

    /**
     * Where value is not null using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function havingNotNull(mixed $column): static;

    /**
     * Where value is not null using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function orHavingNotNull(mixed $column): static;

    /**
     * Where value like using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value like using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value like using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingNotLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value like using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingNotLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingNotStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingNotStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingNotEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingNotEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function havingNotContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orHavingNotContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value is between two values using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param mixed $lower
     * @param mixed $higher
     * @return static Self object
     */
    public function havingBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Where value is between two values using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param mixed $lower
     * @param mixed $higher
     * @return static Self object
     */
    public function orHavingBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Where value is between two values using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param scalar|BaseExpression|Query $lower
     * @param scalar|BaseExpression|Query $higher
     * @return static Self object
     */
    public function havingNotBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Where value is between two values using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param scalar|BaseExpression|Query $lower
     * @param scalar|BaseExpression|Query $higher
     * @return static Self object
     */
    public function orHavingNotBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Perform grouping condition using ```and``` clause
     * @param (Closure(ConditionInterface): void) $group
     * @return static Self object
     */
    public function havingGroup(Closure $group): static;

    /**
     * Perform grouping condition using ```or``` clause
     * @param (Closure(ConditionInterface): void) $group
     * @return static Self object
     */
    public function orHavingGroup(Closure $group): static;

    /**
     * Where values in subquery is exists condition using ```and``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function havingExists(mixed $query): static;

    /**
     * Where values in subquery is exists condition using ```or``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function orHavingExists(mixed $query): static;

    /**
     * Where values in subquery ```not``` exists condition using ```and``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function havingNotExists(mixed $query): static;

    /**
     * Where values in subquery is ```not``` exists condition using ```or``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function orHavingNotExists(mixed $query): static;
}