<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer;

readonly class BelongsToDescriber implements RelationDescriberInterface
{

    public function __construct(
        public ?string $foreignKey = null,
        public ?string $owner_key = null
    )
    {
    }
}