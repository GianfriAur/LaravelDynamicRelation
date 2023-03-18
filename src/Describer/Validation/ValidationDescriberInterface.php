<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Validation;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;

interface ValidationDescriberInterface
{
    /**
     * @param FullRelationDescriber $describer
     * @param bool $status
     * @param ValidationResultEnum|null $result
     * @param string|null $message
     */
    public function __construct(
        FullRelationDescriber $describer,
        bool $status,
        ?ValidationResultEnum $result,
        ?string $message
    );
}