<?php

namespace App\Exceptions;

use App\Services\RouteLoopValidator;
use RuntimeException;

/**
 * Thrown by RouteLoopValidator when assigning a redirect target would make a
 * route's chain longer than RouteLoopValidator::MAX_CHAIN_LENGTH hops.
 */
class TooManyRedirectsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'This redirect would chain more than '.RouteLoopValidator::MAX_CHAIN_LENGTH.' redirects deep.'
        );
    }
}
