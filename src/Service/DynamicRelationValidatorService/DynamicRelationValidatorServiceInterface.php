<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService\DynamicRelationDriverServiceInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Illuminate\Foundation\Application;

interface DynamicRelationValidatorServiceInterface
{
    public function __construct(Application $app, DynamicRelationRegisterServiceInterface $registerService,DynamicRelationDriverServiceInterface $driverService, array $options);

    public function validateFullRelationDescriber(FullRelationDescriber $describer):array;
}