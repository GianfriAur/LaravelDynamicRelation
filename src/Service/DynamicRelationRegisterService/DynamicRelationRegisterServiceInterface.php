<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService;

use Gianfriaur\LaravelDynamicRelation\Describer\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\RelationDescriberInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService\DefaultDynamicRelationBuilderServiceService;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService\DynamicRelationBuilderServiceInterface;
use Illuminate\Foundation\Application;

interface DynamicRelationRegisterServiceInterface
{
    public function __construct(Application $app, DynamicRelationBuilderServiceInterface $builderService, array $options);

    /**
     * @param class-string $entity
     * @param string $relation_name
     * @param class-string $relater
     * @param RelationDescriberInterface $relationDescriber
     * @param string|null $reverse_relation_name
     * @param FullRelationDescriber|null $from
     * @return void
     */
    public function registerRelation(
        string                     $entity,
        string                     $relation_name,
        string                     $relater,
        RelationDescriberInterface $relationDescriber,
        ?string                    $reverse_relation_name = null,
        ?FullRelationDescriber     $from = null
    ): void;

    public function getModelRelation($model, $relation_name): ?array;

    public function getModelRelations($model): ?array;

    public function getRelations(): array;


}