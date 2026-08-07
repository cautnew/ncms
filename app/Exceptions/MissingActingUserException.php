<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown by App\Models\Concerns\HasUserstamps when a model is created,
 * updated, or deleted with no authenticated user to attribute the write to,
 * and the caller didn't supply one explicitly either. In practice this
 * should only surface outside the HTTP layer (seeders, console commands,
 * tests) — every write route is already behind the auth:sanctum middleware.
 */
class MissingActingUserException extends RuntimeException
{
    public function __construct(string $model, string $operation)
    {
        parent::__construct(
            "Cannot {$operation} {$model}: no acting user is authenticated and none was provided explicitly."
        );
    }
}
