<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService;

use Exception;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\BelongsToDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\OneToManyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\OneToOneDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\RelationDescriberInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService\DynamicRelationBuilderServiceInterface;
use Illuminate\Foundation\Application;

class DefaultDynamicRelationRegisterService implements DynamicRelationRegisterServiceInterface
{

    private array $relations = [];

    public function __construct(protected readonly Application $app, protected readonly DynamicRelationBuilderServiceInterface $builderService, protected readonly array $options)
    {
    }

    /**
     * @throws Exception
     */
    public function registerRelation(string $entity, string $relation_name, string $relater, RelationDescriberInterface $relationDescriber, ?string $reverse_relation_name = null, ?FullRelationDescriber $from = null): void
    {
        if (!isset($this->relations[$entity])) {
            $this->relations[$entity] = [];
        }

        $full_relation_describer = new FullRelationDescriber(
            $entity,
            $relater,
            $relationDescriber,
            $relation_name,
            $reverse_relation_name ?? $from->relation_name ?? null
        );

        $this->relations[$entity][$relation_name] = [
            'describer' => $full_relation_describer,
            'callable' => $this->builderService->generateOwnerRelation($relater, $relationDescriber)
        ];

        if ($reverse_relation_name) {
            $inverse_describer = null;
            if ($relationDescriber instanceof OneToManyDescriber || $relationDescriber instanceof OneToOneDescriber) {
                $inverse_describer = new BelongsToDescriber($relationDescriber->foreignKey, $relationDescriber->localKey);
            }

            if ($inverse_describer === null) {
                throw new Exception(get_class($relationDescriber) . ' inverse not supported yet');
            }

            $this->registerRelation($relater, $reverse_relation_name, $entity, $inverse_describer, null, $full_relation_describer);

        }
    }

    public function getModelRelation($model, $relation_name): ?array
    {
        if ($relations = $this->getModelRelations($model)) {
            if (isset($relations[$relation_name])) {
                return $relations[$relation_name];
            }
        }

        return null;
    }

    public function getModelRelations($model): ?array
    {
        if (isset($this->relations[$model])) {
            return $this->relations[$model];
        }
        return null;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }
}