<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService;

use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService\DynamicRelationDriverServiceInterface;
use Illuminate\Foundation\Application;

interface DynamicRelationValidatorServiceInterface
{
    public function __construct(Application $app, DynamicRelationDriverServiceInterface $dynamicRelationDriverService, array $options);
}