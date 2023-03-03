<?php

declare(strict_types=1);

namespace SqlTark\Query;

use Closure;
use SqlTark\Query;
use SqlTark\Expressions\BaseExpression;

/**
 * @method static where(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * @method static orWhere(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * @method static whereNot(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * @method static orWhereNot(array<scalar|BaseExpression|Query,scalar|BaseExpression|Query>|object $pairs)
 * 
 * @method static where(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 * @method static orWhere(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 * @method static whereNot(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 * @method static orWhereNot(scalar|BaseExpression|Query $left, scalar|BaseExpression|Query $right)
 */
interface ConditionInterface extends QueryInterface
{
    /**
     * Where and condtition
     * @return static Self object
     */
    public function and(): static;

    /**
     * Where or condtition
     * @return static Self object
     */
    public function or(): static;

    /**
     * Where not condtition
     * @param bool $value Is not?
     * @return static Self object
     */
    public function not(bool $value = true): static;

    /**
     * Where compare two value using ```and``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function where(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where compare two value using ```or``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function orWhere(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where compare two value using ```not``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function whereNot(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where compare two value ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $left
     * @param ?string $operator
     * @param scalar|BaseExpression|Query $right
     * @return static Self object
     */
    public function orWhereNot(mixed $left, ?string $operator = null, mixed $right = null): static;

    /**
     * Where value is in values using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function whereIn(mixed $column, mixed $values): static;

    /**
     * Where value is in values using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function orWhereIn(mixed $column, mixed $values): static;

    /**
     * Where value is in values using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function whereNotIn(mixed $column, mixed $values): static;

    /**
     * Where value is in values using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param list<scalar|BaseExpression|Query>|Query $values
     * @return static Self object
     */
    public function orWhereNotIn(mixed $column, mixed $values): static;

    /**
     * Where raw expression using ```and``` clause
     * @param string $expression
     * @param null|scalar|BaseExpression|Query ...$bindings
     * @return static Self object
     */
    public function whereRaw(string $expression, mixed ...$bindings): static;

    /**
     * Where raw expression using ```or``` clause
     * @param string $expression
     * @param null|scalar|BaseExpression|Query ...$bindings
     * @return static Self object
     */
    public function orWhereRaw(string $expression, mixed ...$bindings): static;

    /**
     * Where value is null using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function whereNull(mixed $column): static;

    /**
     * Where value is null using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function orWhereNull(mixed $column): static;

    /**
     * Where value is not null using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function whereNotNull(mixed $column): static;

    /**
     * Where value is not null using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @return static Self object
     */
    public function orWhereNotNull(mixed $column): static;

    /**
     * Where value like using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value like using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value like using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereNotLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value like using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereNotLike(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereNotStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value starts with using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereNotStarts(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereNotEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value ends with using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereNotEnds(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function whereNotContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value contains using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return static Self object
     */
    public function orWhereNotContains(mixed $column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null): static;

    /**
     * Where value is between two values using ```and``` clause
     * @param scalar|BaseExpression|Query $column
     * @param mixed $lower
     * @param mixed $higher
     * @return static Self object
     */
    public function whereBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Where value is between two values using ```or``` clause
     * @param scalar|BaseExpression|Query $column
     * @param mixed $lower
     * @param mixed $higher
     * @return static Self object
     */
    public function orWhereBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Where value is between two values using ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param scalar|BaseExpression|Query $lower
     * @param scalar|BaseExpression|Query $higher
     * @return static Self object
     */
    public function whereNotBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Where value is between two values using ```or``` and ```not``` clause
     * @param scalar|BaseExpression|Query $column
     * @param scalar|BaseExpression|Query $lower
     * @param scalar|BaseExpression|Query $higher
     * @return static Self object
     */
    public function orWhereNotBetween(mixed $column, mixed $lower, mixed $higher): static;

    /**
     * Perform grouping condition using ```and``` clause
     * @param (Closure(ConditionInterface): void) $group
     * @return static Self object
     */
    public function whereGroup(Closure $group): static;

    /**
     * Perform grouping condition using ```or``` clause
     * @param (Closure(ConditionInterface): void) $group
     * @return static Self object
     */
    public function orWhereGroup(Closure $group): static;

    /**
     * Where values in subquery is exists condition using ```and``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function whereExists(mixed $query): static;

    /**
     * Where values in subquery is exists condition using ```or``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function orWhereExists(mixed $query): static;

    /**
     * Where values in subquery ```not``` exists condition using ```and``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function whereNotExists(mixed $query): static;

    /**
     * Where values in subquery is ```not``` exists condition using ```or``` clause
     * @param (Closure(Query): void)|Query $query
     * @return static Self object
     */
    public function orWhereNotExists(mixed $query): static;
}