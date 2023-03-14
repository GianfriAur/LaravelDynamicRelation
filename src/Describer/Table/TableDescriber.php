<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Table;

class TableDescriber
{
    /**
     * @param bool $exist
     * @param string $name
     * @param iterable<ColumnDescriber> $columns
     * @param iterable<IndexDescriber> $indexes
     * @param iterable<ForeignKeyDescriber> $foreignKeys
     */
    public function __construct(
        public bool $exist,
        public string $name,
        public iterable $columns,
        public iterable $indexes,
        public iterable $foreignKeys,
    )
    {
    }
}