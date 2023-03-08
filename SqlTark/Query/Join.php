<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Component\JoinType;

class Join extends AbstractQuery implements JoinConditionInterface
{
    use Traits\From, Traits\JoinCondition;

    /**
     * @var JoinType $type
     */
    protected JoinType $type = JoinType::Join;

    /**
     * @return JoinType
     */
    public function getType(): JoinType
    {
        return $this->type;
    }

    /**
     * @param JoinType $type
     * @return static Self object
     */
    public function asType(JoinType $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return static Self object
     */
    public function asInnerJoin(): static
    {
        return $this->asType(JoinType::InnerJoin);
    }

    /**
     * @return static Self object
     */
    public function asLeftJoin(): static
    {
        return $this->asType(JoinType::LeftJoin);
    }

    /**
     * @return static Self object
     */
    public function asRightJoin(): static
    {
        return $this->asType(JoinType::RightJoin);
    }

    /**
     * @return static Self object
     */
    public function asOuterJoin(): static
    {
        return $this->asType(JoinType::OuterJoin);
    }

    /**
     * @return static Self object
     */
    public function asNaturalJoin(): static
    {
        return $this->asType(JoinType::NaturalJoin);
    }

    /**
     * @return static Self object
     */
    public function asLeftOuterJoin(): static
    {
        return $this->asType(JoinType::LeftOuterJoin);
    }

    /**
     * @return static Self object
     */
    public function asRightOuterJoin(): static
    {
        return $this->asType(JoinType::RightOuterJoin);
    }

    /**
     * @return static Self object
     */
    public function asFullOuterJoin(): static
    {
        return $this->asType(JoinType::FullOuterJoin);
    }
}