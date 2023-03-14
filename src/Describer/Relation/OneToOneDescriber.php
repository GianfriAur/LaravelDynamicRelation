<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Relation;

readonly class OneToOneDescriber implements RelationDescriberInterface
{

    public function __construct(
        public ?string $foreignKey = null,
        public ?string $localKey = null
    )
    {
    }
}