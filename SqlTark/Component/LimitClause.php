<?php
declare(strict_types = 1);
namespace SqlTark\Component;

class LimitClause extends AbstractComponent
{
    /**
     * @var int $limit
     */
    protected int $limit = 0;

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $value
     * @return static
     */
    public function setLimit(int $value): static
    {
        $this->limit = $value < 0 ? 0 : $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasLimit(): bool
    {
        return $this->limit > 0;
    }
}