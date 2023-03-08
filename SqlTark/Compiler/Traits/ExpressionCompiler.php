<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Utilities\Helper;
use SqlTark\Expressions\Column;
use SqlTark\Expressions\Literal;
use SqlTark\Expressions\Variable;

trait ExpressionCompiler
{
    /**
     * {@inheritdoc}
     */
    public function compileLiteral(Literal $literal): string
    {
        $value = $literal->getValue();
        $result = $this->quote($value);

        return $this->wrapFunction($result, $literal->getWrap());
    }

    /**
     * {@inheritdoc}
     */
    public function compileColumn(Column $column, bool $withAlias = true): string
    {
        $result = trim($column->getName());
        if (empty($result) || $result == '*') {
            return $this->wrapFunction('*', $column->getWrap());
        }

        $aliasSplit = array_map(
            function ($item) {
                return $this->wrapIdentifier($item);
            },
            Helper::extractAlias($result)
        );

        $columnExression = $this->wrapFunction($aliasSplit[0], $column->getWrap());
        if ($withAlias && isset($aliasSplit[1])) {
            $columnExression .= ' AS ' . $aliasSplit[1];
        }

        return $columnExression;
    }

    /**
     * {@inheritdoc}
     */
    public function compileRaw(string $expression, iterable $bindings = []): string
    {
        $expression = trim($expression, " \t\n\r\0\x0B,");
        return Helper::replaceAll($expression, $this->parameterPlaceholder, function ($index) use ($bindings) {
            return $this->compileExpression($bindings[$index], false);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function compileVariable(Variable $variable): string
    {
        if(is_null($variable->getName())) {
            return $this->wrapFunction($this->parameterPlaceholder, $variable->getWrap());
        }

        $result = trim($variable->getName());

        if (isset($result[0]) && $result[0] != $this->variablePrefix) {
            $result = $this->variablePrefix . $result;
        }

        return $this->wrapFunction($result, $variable->getWrap());
    }

    /**
     * @param string $value
     * @param ?string $wrapper
     * @return string
     */
    protected function wrapFunction(string $value, ?string $wrapper): string
    {
        if (is_string($wrapper)) {
            return $wrapper . "($value)";
        }

        return $value;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function wrapIdentifier(string $value): string
    {
        $splitName = [];
        foreach (explode('.', $value) as $item) {
            $item = trim($item);
            if (!empty($item)) {
                if ($item != '*') {
                    if ($item[0] != $this->openingIdentifier) {
                        $item = $this->openingIdentifier . $item;
                    }

                    if ($item[strlen($item) - 1] != $this->closingIdentifier) {
                        $item = $item . $this->closingIdentifier;
                    }
                }
                $splitName[] = $item;
            }
        }

        return join('.', $splitName);
    }
}