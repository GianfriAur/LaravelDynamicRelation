<?php

namespace Gianfriaur\LaravelDynamicRelation\Exception;


use Gianfriaur\LaravelDynamicRelation\LaravelDynamicRelationServiceProvider;
use Throwable;

class DynamicRelationMissingOptionException extends DynamicRelationException
{
    public function __construct(string $option_name, mixed $class, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "DynamicRelation cannot load services, ".get_class($class)." missing option named '$option_name' in file 'config/" . LaravelDynamicRelationServiceProvider::CONFIG_FILE_NANE . '\'',
            $code, $previous);
    }
}