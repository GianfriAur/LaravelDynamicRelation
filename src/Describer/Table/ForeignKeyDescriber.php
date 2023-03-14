<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Table;

class ForeignKeyDescriber
{
    public function __construct(
        public string $name ,
        public string $colum ,
        public string $destination_table,
        public string $destination_colum
    )
    {
    }
}