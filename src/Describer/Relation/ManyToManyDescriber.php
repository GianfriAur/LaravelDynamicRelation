<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Relation;

readonly class ManyToManyDescriber implements RelationDescriberInterface
{
    public function __construct(
        public ?string $table = null,
        public ?string $foreignPivotKey = null,
        public ?string $relatedPivotKey = null,
        public ?string $parentKey = null,
        public ?string $relatedKey = null,
        public ?string $relation = null)
    {
    }
}