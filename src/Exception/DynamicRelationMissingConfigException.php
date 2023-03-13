<?php

namespace Gianfriaur\LaravelDynamicRelation\Exception;


use Gianfriaur\LaravelDynamicRelation\LaravelDynamicRelationServiceProvider;
use Throwable;

class DynamicRelationMissingConfigException extends DynamicRelationException
{
    public function __construct(string $config_name = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "DynamicRelation cannot load services, $config_name config in file 'config/" . LaravelDynamicRelationServiceProvider::CONFIG_FILE_NANE . '\'',
            $code, $previous);
    }
}