<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Helper;
use SqlTark\Expressions\BaseExpression;

class CompareClause extends AbstractCondition
{
    /**
     * @var BaseExpression|Query $left
     */
    protected BaseExpression|Query $left;

    /**
     * @var string $operator
     */
    protected $operator;

    /**
     * @var BaseExpression|Query $right
     */
    protected BaseExpression|Query $right;

    /**
     * @return BaseExpression|Query
     */
    public function getLeft(): BaseExpression|Query
    {
        return $this->left;
    }

    /**
     * @param BaseExpression|Query $value
     * @return void
     */
    public function setLeft(BaseExpression|Query $value): void
    {
        $this->left = $value;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setOperator(string $value): void
    {
        $this->operator = $value;
    }

    /**
     * @return BaseExpression|Query
     */
    public function getRight(): BaseExpression|Query
    {
        return $this->right;
    }

    /**
     * @param BaseExpression|Query $value
     * @return void
     */
    public function setRight(BaseExpression|Query $value): void
    {
        $this->right = $value;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->left = Helper::cloneObject($this->left);
        $this->right = Helper::cloneObject($this->right);
    }
}
