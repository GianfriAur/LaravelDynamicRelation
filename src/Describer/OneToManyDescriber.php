<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer;

readonly class OneToManyDescriber implements RelationDescriberInterface
{

    public function __construct(
        public ?string $foreignKey = null,
        public ?string $localKey = null
    )
    {
    }
}