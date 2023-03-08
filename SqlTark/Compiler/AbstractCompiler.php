<?php

declare(strict_types=1);

use SqlTark\Query;
use SqlTark\Expressions\Raw;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\Column;
use SqlTark\Expressions\Literal;
use SqlTark\Expressions\Variable;
use SqlTark\Expressions\AbstractExpression;

abstract class AbstractCompiler
{
    /**
     * @var string $openingIdentifier
     */
    protected string $openingIdentifier;

    /**
     * @var string $closingIdentifier
     */
    protected string $closingIdentifier;

    /**
     * @var string $parameterPlaceholder
     */
    protected string $parameterPlaceholder = '?';

    /**
     * @var string $variablePrefix
     */
    protected string $variablePrefix = ':';

    /**
     * @var string $escapeCharacter
     */
    protected string $escapeCharacter = '\\';

    /**
     * @var string $dummyTable
     */
    protected ?string $dummyTable = null;

    /**
     * @var bool $fromTableRequired
     */
    protected bool $fromTableRequired = false;

    /**
     * @var string $maxValue
     */
    protected string $maxValue = '18446744073709551615';

    /**
     * @param AbstractExpression|Query $expression
     * @return string
     */
    public function compileExpression(AbstractExpression|Query $expression, bool $withAlias = true): string
    {
        if($expression instanceof Literal)
            return $this->compileLiteral($expression);

        if($expression instanceof Column)
            return $this->compileColumn($expression, $withAlias);

        if($expression instanceof Raw)
            return $this->compileRaw($expression->getExpression(), $expression->getBindings());

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
     * @param Query $query
     * @return string
     */
    public function compileQuery(Query $query): string
    {
        return match($query->getMethod()) {
            MethodType::Select => $this->compileSelectQuery($query),
            MethodType::Update => $this->compileUpdateQuery($query),
            MethodType::Insert => $this->compileInsertQuery($query),
            MethodType::Delete => $this->compileDeleteQuery($query),

            default => Helper::throwInvalidArgumentException(
                "Unable to compile query with %s method.",
                $query->getMethod()
            )
        };
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
    public abstract function compileColumn(Column $column, bool $withAlias = true): string;

    /**
     * @param string $expression
     * @param list<AbstractExpression|Query> $bindings
     * @return string
     */
    public abstract function compileRaw(string $expression, iterable $bindings = []): string;

    /**
     * @param Variable $variable
     * @return string
     */
    public abstract function compileVariable(Variable $variable): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileSelectQuery(Query $query): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileInsertQuery(Query $query): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileUpdateQuery(Query $query): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileDeleteQuery(Query $query): string;

    /**
     * @param null|scalar|DateTimeInterface $value
     * @param bool $quoteLike
     * @return string
     */
    public abstract function quote(mixed $value, bool $quoteLike = false): string;
}