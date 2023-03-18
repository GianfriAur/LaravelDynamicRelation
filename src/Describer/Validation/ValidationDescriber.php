<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Validation;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;

readonly class ValidationDescriber implements ValidationDescriberInterface
{

    public function __construct(
        public FullRelationDescriber $describer,
        public bool $status,
        public ?ValidationResultEnum $result,
        public ?string $message
    )
    {
    }
}