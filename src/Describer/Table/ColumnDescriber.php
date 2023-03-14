<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Table;

class ColumnDescriber
{
    public function __construct(
        public string $name ,
        public string $type ,
        public bool $nullable,
        public ?string $key = null,
        public ?string $default = null,
        public ?string $extra = null
    )
    {
    }
}