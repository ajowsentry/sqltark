<?php

declare(strict_types=1);

namespace SqlTark;

use SqlTark\Expressions\Raw;
use SqlTark\Expressions\Column;
use SqlTark\Expressions\Literal;
use SqlTark\Expressions\Variable;

final class Expressions
{
    /**
     * @param string $name
     * @param ?string $wrap
     * @return Column
     */
    public static function column(string $name, ?string $wrap = null): Column
    {
        return (new Column($name))->wrap($wrap);
    }

    /**
     * @param string $name
     * @param ?string $wrap
     * @return Variable
     */
    public static function variable(string $name = null, ?string $wrap = null): Variable
    {
        return (new Variable($name))->wrap($wrap);
    }

    /**
     * @param string $value
     * @param ?string $wrap
     * @return Literal
     */
    public static function literal(string $value, ?string $wrap = null): Literal
    {
        return (new Literal($value))->wrap($wrap);
    }

    /**
     * @param string $expression
     * @return Raw
     */
    public static function raw(string $expression): Raw
    {
        return new Raw($expression);
    }

    private function __construct() { }
}