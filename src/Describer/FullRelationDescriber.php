<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer;

class FullRelationDescriber
{
    public function __construct(
        public string $ownerClass ,
        public string $relatedClass,
        public RelationDescriberInterface $describer,
        public string $relation_name,
        public ?string $inverse_relation_name
    ){

    }
}