<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\OneToManyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\ColumnDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\ForeignKeyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\IndexDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\TableDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationResultEnum;
use Illuminate\Support\Collection;

class TableExistValidator implements ValidatorInterface
{

    public function handle(FullRelationDescriber $ownerDescriber, TableDescriber $ownerTableDescriber, TableDescriber $relatedTableDescriber): bool
    {
        return true;
    }

    public function validate(
        FullRelationDescriber $ownerDescriber,
        TableDescriber $ownerTableDescriber,
        TableDescriber $relatedTableDescriber
    ): ValidationDescriber|array
    {

        $validations = [];

        if ($ownerTableDescriber->exist){
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                true,
                ValidationResultEnum::INFO,
                'table '. $ownerTableDescriber->name .' exist'
            );
        }else{
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                true,
                ValidationResultEnum::ERROR,
                'table '. $ownerTableDescriber->name .' not exist'
            );
        }


        if ($relatedTableDescriber->exist){
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                true,
                ValidationResultEnum::INFO,
                'table '. $relatedTableDescriber->name .' exist'
            );
        }else{
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                true,
                ValidationResultEnum::ERROR,
                'table '. $relatedTableDescriber->name .' not exist'
            );
        }


        return $validations;
    }
}