<?php

declare(strict_types=1);

namespace SqlTark\Compiler;

class SqlServerCompiler extends AbstractCompiler
{
    use Traits\ExpressionCompiler,
        SqlServer\SelectQueryCompiler,
        Traits\InsertQueryCompiler,
        Traits\UpdateQueryCompiler,
        Traits\DeleteQueryCompiler;

    /**
     * {@inheritdoc}
     */
    protected string $openingIdentifier = '[';

    /**
     * {@inheritdoc}
     */
    protected string $closingIdentifier = ']';

    /**
     * {@inheritdoc}
     */
    public function quote(mixed $value, bool $quoteLike = false): string
    {
        if (is_string($value)) {
            return "'" . str_replace("'", "''", $value) . "'";
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