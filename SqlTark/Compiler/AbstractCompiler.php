<?php

declare(strict_types=1);

use SqlTark\Query;
use SqlTark\Expressions\Raw;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\Column;
use SqlTark\Expressions\Literal;
use SqlTark\Expressions\Variable;
use SqlTark\Expressions\AbstractExpression;

abstract class AbstractCompiler
{
    /**
     * @param AbstractExpression|Query $expression
     * @return string
     */
    public function compileExpression(AbstractExpression|Query $expression): string
    {
        if($expression instanceof Literal)
            return $this->compileLiteral($expression);

        if($expression instanceof Column)
            return $this->compileColumn($expression);

        if($expression instanceof Raw)
            return $this->compileRaw($expression);

        if($expression instanceof Variable)
            return $this->compileVariable($expression);

        if($expression instanceof Query)
            return $this->compileQuery($expression);

        Helper::throwInvalidArgumentException(
            "Could not resolve expression from '%s' type.",
            $expression
        );
    }

    /**
     * @param Literal $literal
     * @return string
     */
    public abstract function compileLiteral(Literal $literal): string;

    /**
     * @param Column $column
     * @return string
     */
    public abstract function compileColumn(Column $column): string;

    /**
     * @param Raw $raw
     * @return string
     */
    public abstract function compileRaw(Raw $raw): string;

    /**
     * @param Variable $variable
     * @return string
     */
    public abstract function compileVariable(Variable $variable): string;

    /**
     * @param Query $query
     */
    public abstract function compileQuery(Query $query): string;
}