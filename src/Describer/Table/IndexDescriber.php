<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Table;

class IndexDescriber
{
    public function __construct(
        public string $name ,
        public string $key_name ,
        public bool $primary,
        public bool $unique,
        public ?int $cardinality ,
        public string $type
    )
    {
    }
}