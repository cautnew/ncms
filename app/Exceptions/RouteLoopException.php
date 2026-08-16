<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by RouteLoopValidator when assigning a redirect target would make a
 * route's chain loop back on itself (directly or through intermediate hops).
 */
class RouteLoopException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('This redirect would create an infinite loop.');
    }
}
