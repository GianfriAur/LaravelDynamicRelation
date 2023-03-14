<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\RelationDescriberInterface;
use Illuminate\Foundation\Application;

interface DynamicRelationBuilderServiceInterface
{
    public function __construct(Application $app, array $options);

    public function generateOwnerRelation($associated_class, RelationDescriberInterface $describer ): callable;

}