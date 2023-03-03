<?php

declare(strict_types=1);

namespace SqlTark\Query;

enum MethodType
{
    /**
     * Select query method
     */
    case Select;
    
    /**
     * Aggregate select query method
     */
    case Aggregate;
    
    /**
     * Join query clause
     */
    case Join;
    
    /**
     * Insert query method
     */
    case Insert;
    
    /**
     * Update query method
     */
    case Update;

    /**
     * Delete query method
     */
    case Delete;

    /**
     * Auto detect query method
     */
    case Auto;
}