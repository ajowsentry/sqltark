<?php

declare(strict_types=1);

namespace SqlTark\Compiler\MySql;

use AbstractCompiler;
use SqlTark\Compiler\Traits;

class Compiler extends AbstractCompiler
{
    use Traits\ExpressionCompiler,
        Traits\SelectQueryCompiler,
        Traits\InsertQueryCompiler,
        Traits\UpdateQueryCompiler,
        Traits\DeleteQueryCompiler;

    /**
     * {@inheritdoc}
     */
    protected string $openingIdentifier = '`';

    /**
     * {@inheritdoc}
     */
    protected string $closingIdentifier = '`';

    /**
     * {@inheritdoc}
     */
    public function quote(mixed $value, bool $quoteLike = false): string
    {
        if (is_string($value)) {
            $result = str_replace(
                ['\\', "\r", "\n", "\t", "\x08", "'", "\"", "\x1A", "\x00"],
                ['\\\\', '\r', '\n', '\t', '\b', "\'", '\"', '\Z', '\0'],
                $value
            );

            if($quoteLike) {
                $result = str_replace(['\%', '\_'], ['%', '_'], $result);
            }

            return "'{$result}'";
        } elseif (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        } elseif (is_scalar($value)) {
            return (string) $value;
        } elseif (is_null($value)) {
            return 'NULL';
        } elseif ($value instanceof \DateTimeInterface) {
            return "'" . $value->format('Y-m-d H:i:s') . "'";
        }

        // Helper::throwInvalidArgumentException("Could not resolve value from '%s' type.", $value);
    }
}