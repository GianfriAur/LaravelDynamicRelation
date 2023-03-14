<?php

namespace Gianfriaur\LaravelDynamicRelation\Exception;

use Throwable;

class NotImplementedYetException extends DynamicRelationException
{
    public function __construct(string $message = "Not implemented yet", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}