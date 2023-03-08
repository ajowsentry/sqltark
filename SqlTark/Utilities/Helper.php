<?php

declare(strict_types=1);

namespace SqlTark\Utilities;

use Closure;
use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Expressions;
use InvalidArgumentException;
use SqlTark\Query\AbstractQuery;
use SqlTark\Query\QueryInterface;
use SqlTark\Query\WhereCondition;
use SqlTark\Query\HavingCondition;
use SqlTark\Component\ComponentType;
use SqlTark\Query\ConditionInterface;
use SqlTark\Expressions\AbstractExpression;
use SqlTark\Query\Join;

final class Helper
{
    /**
     * @param mixed $expression
     * @param bool $stringAsColumn
     * @return Query|AbstractExpression
     */
    public static function resolveExpression(mixed $expression, bool $stringAsColumn = false): Query|AbstractExpression
    {
        if ($expression instanceof Query || $expression instanceof AbstractExpression) {
            return $expression;
        }

        elseif (is_string($expression) && $stringAsColumn) {
            return Expressions::column($expression);
        }

        elseif (is_null($expression) || is_scalar($expression) || $expression instanceof DateTimeInterface) {
            return Expressions::literal($expression);
        }

        self::throwInvalidArgumentException(
            "Could not resolve expression from type '%s'",
            $expression
        );
    }

    /**
     * @param list<mixed> $expressionList
     * @param bool $stringAsColumn
     * @return list<Query|AbstractExpression>
     */
    public static function resolveExpressionList(iterable $expressionList, bool $stringAsColumn = false): array
    {
        $resolved = [];
        foreach($expressionList as $item)
            array_push($resolved, self::resolveExpression($item, $stringAsColumn));
        
        return $resolved;
    }

    /**
     * @param (Closure(Query):void)|Query $value
     * @param AbstractQuery $query
     * @return Query
     */
    public static function resolveQuery(Closure|Query $value, AbstractQuery $query): Query
    {
        if($value instanceof Closure) {
            $child = $query->newChild();
            $value($child);
            return $child;
        }

        return $value;
    }

    /**
     * @param (Closure(Join):void)|Join $value
     * @param AbstractQuery $query
     * @return Join
     */
    public static function resolveJoin(Closure|Join $value, AbstractQuery $query): Join
    {
        if($value instanceof Closure) {
            $child = new Join;

            $child->setParent($query);
            $value($child);

            return $child;
        }

        return $value;
    }

    /**
     * @param (Closure(ConditionInterface):void)|ConditionInterface $value
     * @param AbstractQuery $query
     * @return ConditionInterface
     */
    public static function resolveCondition(Closure|ConditionInterface $value, AbstractQuery $query): ConditionInterface
    {
        if($value instanceof Closure) {
            $condition = $query->getConditionComponent() == ComponentType::Having
                ? new HavingCondition
                : new WhereCondition;

            $condition->setParent($query);
            $value($condition);

            return $condition;
        }

        return $value;
    }

    /**
     * @param string $expression
     * @return list<string>
     */
    public static function extractAlias(string $expression): array
    {
        return preg_split('/\s+as\s+/i', trim($expression), PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function getType(mixed $value): string
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    /**
     * @template T
     * @param T $value
     * @return T
     */
    public static function clone(mixed $value): mixed
    {
        return is_object($value) ? clone $value : $value;
    }

    /**
     * @param string $message
     * @param mixed  $object
     * @return never
     */
    public static function throwInvalidArgumentException(string $message, mixed $object): never
    {
        $type = self::getType($object);
        throw new InvalidArgumentException(sprintf($message, $type));
    }

    private function __construct() { }
}