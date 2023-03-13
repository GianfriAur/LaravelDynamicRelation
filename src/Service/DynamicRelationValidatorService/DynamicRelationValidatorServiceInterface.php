<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService;

use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Illuminate\Foundation\Application;

interface DynamicRelationValidatorServiceInterface
{
    public function __construct(Application $app, DynamicRelationRegisterServiceInterface $dynamicRelationRegisterService, array $options);
}