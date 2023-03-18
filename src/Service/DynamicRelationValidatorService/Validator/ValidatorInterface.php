<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\TableDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationDescriber;

interface ValidatorInterface
{

    public function handle(
        FullRelationDescriber $ownerDescriber,
        TableDescriber $ownerTableDescriber,
        TableDescriber $relatedTableDescriber
    ):bool;

    /**
     * @param FullRelationDescriber $ownerDescriber
     * @param TableDescriber $ownerTableDescriber
     * @param TableDescriber $relatedTableDescriber
     * @return ValidationDescriber|array<ValidationDescriber>
     */
    public function validate(
        FullRelationDescriber $ownerDescriber,
        TableDescriber $ownerTableDescriber,
        TableDescriber $relatedTableDescriber
    ):ValidationDescriber|array;
}