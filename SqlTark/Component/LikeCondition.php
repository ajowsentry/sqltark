<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class LikeCondition extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected AbstractExpression|Query $column;

    /**
     * @var LikeType $type
     */
    protected LikeType $type = LikeType::Like;

    /**
     * @var string $value
     */
    protected string $value;

    /**
     * @var bool $caseSensitive
     */
    protected bool $caseSensitive = false;

    /**
     * @var ?string $escapeCharacter
     */
    protected ?string $escapeCharacter;

    /**
     * @return bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setCaseSensitive(bool $value): void
    {
        $this->caseSensitive = $value;
    }

    /**
     * @return ?string
     */
    public function getEscapeCharacter(): ?string
    {
        return $this->escapeCharacter;
    }

    /**
     * @param ?string $value
     * @return void
     */
    public function setEscapeCharacter(?string $value): void
    {
        $this->escapeCharacter = $value;
    }

    /**
     * @return AbstractExpression|Query
     */
    public function getColumn(): AbstractExpression|Query
    {
        return $this->column;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setColumn(AbstractExpression|Query $value): void
    {
        $this->column = $value;
    }

    /**
     * @return LikeType
     */
    public function getType(): LikeType
    {
        return $this->type;
    }

    /**
     * @param LikeType $value
     * @return void
     */
    public function setType(LikeType $value): void
    {
        $this->type = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->column = Helper::clone($this->column);
    }
}
