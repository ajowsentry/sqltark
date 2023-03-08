<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Expressions\AbstractExpression;

interface JoinConditionInterface extends ConditionInterface
{
    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function on(mixed $left, string $operator, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOn(mixed $left, string $operator, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function notOn(mixed $left, string $operator, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orNotOn(mixed $left, string $operator, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function notOnEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orNotOnEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onGreaterThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnGreaterThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onGreaterEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnGreaterEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onLesserThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnLesserThan(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onLesserEquals(mixed $left, mixed $right): static;

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnLesserEquals(mixed $left, mixed $right): static;

}