<?php

declare(strict_types=1);

namespace SqlTark\Component;

enum JoinType: int
{
    /**
     * Join clause
     */
    case Join = 0;

    /**
     * Inner Join clause
     */
    case InnerJoin = 1;
    
    /**
     * Left Join clause
     */
    case LeftJoin = 2;
    
    /**
     * Right Join clause
     */
    case RightJoin = 3;
    
    /**
     * Outer Join clause
     */
    case OuterJoin = 4;
    
    /**
     * Cross Join clause
     */
    case CrossJoin = 5;
    
    /**
     * Natural Join clause
     */
    case NaturalJoin = 6;
    
    /**
     * Left Outer Join clause
     */
    case LeftOuterJoin = 7;
    
    /**
     * Right Outer Join clause
     */
    case RightOuterJoin = 8;
    
    /**
     * Full Join clause
     */
    case FullOuterJoin = 9;

    public function syntaxOf(): string
    {
        return match($this) {
            self::Join           => 'JOIN',
            self::InnerJoin      => 'INNER JOIN',
            self::LeftJoin       => 'LEFT JOIN',
            self::RightJoin      => 'RIGHT JOIN',
            self::OuterJoin      => 'OUTER JOIN',
            self::CrossJoin      => 'CROSS JOIN',
            self::NaturalJoin    => 'NATURAL JOIN',
            self::LeftOuterJoin  => 'LEFT OUTER JOIN',
            self::RightOuterJoin => 'RIGHT OUTER JOIN',
            self::FullOuterJoin  => 'FULL OUTER JOIN',
        };
    }
}
