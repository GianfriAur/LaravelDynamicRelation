<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService;

use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Illuminate\Foundation\Application;

readonly class PgsqlDynamicRelationValidatorService implements DynamicRelationValidatorServiceInterface
{
    public function __construct(protected Application $app, protected DynamicRelationRegisterServiceInterface $dynamicRelationRegisterService, protected array $options)
    {
    }
}