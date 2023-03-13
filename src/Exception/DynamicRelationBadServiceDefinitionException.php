<?php

namespace Gianfriaur\LaravelDynamicRelation\Exception;


use Gianfriaur\LaravelDynamicRelation\LaravelDynamicRelationServiceProvider;
use Throwable;

class DynamicRelationBadServiceDefinitionException extends DynamicRelationException
{
    public function __construct(string $service_name = "", string $reason ="", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "DynamicRelation cannot load services, $service_name because $reason, in file 'config/" . LaravelDynamicRelationServiceProvider::CONFIG_FILE_NANE . '\'',
            $code, $previous);
    }
}