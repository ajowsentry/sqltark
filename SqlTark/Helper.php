<?php

declare(strict_types=1);

namespace SqlTark;

use SqlTark\Query\Join;
use SqlTark\Expressions;
use SqlTark\Query\Query;
use InvalidArgumentException;
use SqlTark\Expressions\BaseExpression;

final class Helper
{
    private function __construct() { }

    /**
     * @param iterable<mixed,mixed>|object $iterable
     * @return array<mixed>
     */
    public static function flatten(mixed $iterable): array
    {
        $result = [];
        foreach ($iterable as $item) {
            if (is_iterable($item) || is_object($item)) {
                foreach (self::flatten($item) as $item2) {
                    $result[] = $item2;
                }
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @param ?string $subject
     * @param string $match
     * @param callable $callback
     * @return string
     */
    public static function replaceAll(?string $subject, string $match, callable $callback): string
    {
        if (empty($subject) || strpos($subject, $match) === false) {
            return (string) $subject;
        }

        $splitted = explode($match, $subject);

        $splitProcess = [];
        for ($i = 1; $i < count($splitted); $i++) {
            $splitProcess[] = $callback($i - 1) . $splitted[$i];
        }

        $result = array_reduce($splitProcess, function ($acc, $item) {
            return $acc . $item;
        }, $splitted[0]);

        return $result;
    }

    /**
     * @param string $separator
     * @param array<mixed,mixed>|iterable<mixed> $iterable
     * @return string
     */
    public static function joinIterable(string $separator, mixed $iterable): string
    {
        if (is_array($iterable) && !isset($iterable[0])) {
            $iterable = array_values($iterable);
        }

        $result = '';
        foreach ($iterable as $index => $item) {
            $result .= $index == 0 ? $item : ($separator . $item);
        }

        return $result;
    }

    /**
     * @param iterable<mixed,mixed>|object $iterable
     * @return int
     */
    public static function countIterable(mixed $iterable): int
    {
        if(is_countable($iterable)) {
            return count($iterable);
        }

        $count = 0;
        foreach ($iterable as $_item) $count++;
        return $count;
    }

    /**
     * @template T
     * @param T $value
     * @param int $times
     * @return T[]
     */
    public static function repeat(mixed $value, int $times): array
    {
        $result = [];
        while ($times--) array_push($result, $value);
        return $result;
    }

    /**
     * @param string $sql
     * @param string $placeholder
     * @param array<mixed> $bindings
     * @return string
     */
    public static function expandParameters(string $sql, string $placeholder, array $bindings): string
    {
        return self::replaceAll($sql, $placeholder, function ($i) use ($bindings, $placeholder) {

            $parameter = $bindings[$i];
            if (is_iterable($parameter)) {
                $count = self::countIterable($parameter);
                return self::joinIterable(',', self::repeat($placeholder, $count));
            }

            return (string) $placeholder;
        });
    }

    /**
     * @param string $expression
     * @return string[]
     */
    public static function expandExpression(string $expression): array
    {
        preg_match("/^(?:\w+\.){1,2}{(.*)}/", $expression, $matches);

        if (count($matches) == 0) {
            return [$expression];
        }

        $table = substr($expression, strpos($expression, '.{'));
        $captures = [];
        foreach (explode(',', $matches[1]) as $item) {
            $item = trim($item);
            if (!empty($item)) {
                $captures[] = $item;
            }
        }

        return array_map(function ($item) use ($table) {
            return "{$table}.{$item}";
        }, $captures);
    }

    public static function replaceIdentifierUnlessEscaped(string $input, string $escapeCharacter, string $identifier, string $newIndentifier): string
    {
        $escapeCharacter = preg_quote($escapeCharacter);
        $identifier = preg_quote($identifier);

        $pattern = "/(?<!$escapeCharacter)$identifier/";
        $nonEscapedReplace = preg_replace($pattern, $newIndentifier, $input);

        $pattern = "/$escapeCharacter$identifier/";
        return preg_replace($pattern, $identifier, $nonEscapedReplace);
    }

    // public static function resolveExpression($expr, string $name)
    // {
    //     if (is_string($expr)) {
    //         $expr = Expressions::column($expr);
    //     } elseif (is_scalar($expr) || is_null($expr) || $expr instanceof \DateTime) {
    //         $expr = Expressions::literal($expr);
    //     } elseif (!($expr instanceof BaseExpression || $expr instanceof Query)) {
    //         $type = self::getType($expr);
    //         throw new InvalidArgumentException(
    //             "Could not resolve '$type' for $name parameter."
    //         );
    //     }

    //     return $expr;
    // }

    // public static function resolveLiteral($expr, string $name)
    // {
    //     if (is_scalar($expr) || is_null($expr) || $expr instanceof \DateTime) {
    //         $expr = Expressions::literal($expr);
    //     } elseif (!($expr instanceof BaseExpression || $expr instanceof Query)) {
    //         $type = self::getType($expr);
    //         throw new InvalidArgumentException(
    //             "Could not resolve '$type' for $name parameter."
    //         );
    //     }

    //     return $expr;
    // }

    // public static function resolveQuery($callback, $ctx)
    // {
    //     if(is_callable($callback))
    //     {
    //         $callback = $callback($ctx->newChild());
    //         if(!($callback instanceof Query))
    //         {
    //             $class = Helper::getType($callback);
    //             throw new InvalidArgumentException(
    //                 "Invalid return from callback. Expected 'Query' found '$class'"
    //             );
    //         }
    //     }
        
    //     return $callback;
    // }

    // public static function resolveJoin($callback, $ctx)
    // {
    //     if(is_callable($callback))
    //     {
    //         $child = new Join;
    //         $child->setParent($ctx);
    //         $callback = $callback($child);
    //         if(!($callback instanceof Join))
    //         {
    //             $class = Helper::getType($callback);
    //             throw new InvalidArgumentException(
    //                 "Invalid return from callback. Expected 'Join' found '$class'"
    //             );
    //         }
    //     }
        
    //     return $callback;
    // }

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
    public static function cloneObject(mixed $value): mixed
    {
        return is_object($value) ? clone $value : $value;
    }
}
