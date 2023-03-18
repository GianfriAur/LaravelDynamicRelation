<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\BelongsToDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\OneToManyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\ColumnDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\ForeignKeyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\IndexDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\TableDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationResultEnum;
use Illuminate\Support\Collection;

class BelongsToRelationValidator implements ValidatorInterface
{

    public function handle(FullRelationDescriber $ownerDescriber, TableDescriber $ownerTableDescriber, TableDescriber $relatedTableDescriber): bool
    {
        return $ownerDescriber->describer instanceof BelongsToDescriber;
    }

    public function validate(
        FullRelationDescriber $ownerDescriber,
        TableDescriber $ownerTableDescriber,
        TableDescriber $relatedTableDescriber
    ): ValidationDescriber|array
    {
        $validations = [];

        //related has column

        if (
            count(
                (new Collection($ownerTableDescriber->columns))
                    ->filter(fn(ColumnDescriber $columnDescriber) =>
                        $columnDescriber->name === $ownerDescriber->describer->foreignKey
                    )
            ) === 1
        ){
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                true,
                ValidationResultEnum::INFO,
                $ownerTableDescriber->name .' table has column ' . $ownerDescriber->describer->foreignKey
            );
        }else{
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                false,
                ValidationResultEnum::ERROR,
                $ownerTableDescriber->name .' table hasn\'t column ' . $ownerDescriber->describer->foreignKey
            );
        }

        //related has index

        if (
            count(
                (new Collection($ownerTableDescriber->indexes))
                    ->filter(fn(IndexDescriber $indexDescriber) =>
                        $indexDescriber->name === $ownerDescriber->describer->foreignKey
                    )
            ) === 1
        ){
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                true,
                ValidationResultEnum::INFO,
                $ownerTableDescriber->name .' table has index on  ' . $ownerDescriber->describer->foreignKey
            );

            //  //related has not unique index
            if (
                count(
                    (new Collection($ownerTableDescriber->indexes))
                        ->filter(fn(IndexDescriber $indexDescriber) =>
                            $indexDescriber->name === $ownerDescriber->describer->foreignKey &&
                            $indexDescriber->unique === false
                        )
                ) === 1
            ){
                $validations[] = new ValidationDescriber(
                    $ownerDescriber,
                    true,
                    ValidationResultEnum::INFO,
                    $ownerTableDescriber->name .' table has not unique index on ' . $ownerDescriber->describer->foreignKey
                );
            }else{
                $validations[] = new ValidationDescriber(
                    $ownerDescriber,
                    false,
                    ValidationResultEnum::ERROR,
                    $ownerTableDescriber->name .' table has unique index on ' . $ownerDescriber->describer->foreignKey
                );
            }

        }else{
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                false,
                ValidationResultEnum::ERROR,
                $ownerTableDescriber->name .' table hasn\'t index on  ' . $ownerDescriber->describer->foreignKey
            );
        }

        //related has foreignKey

        if (
            count(
                (new Collection($ownerTableDescriber->foreignKeys))
                    ->filter(fn(ForeignKeyDescriber $foreignKeyDescriber) =>
                        $foreignKeyDescriber->colum === $ownerDescriber->describer->foreignKey &&
                        $foreignKeyDescriber->destination_table === $relatedTableDescriber->name
                    )
            ) === 1
        ){
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                true,
                ValidationResultEnum::INFO,
                $ownerTableDescriber->name .' table has foreignKey on ' . $ownerDescriber->describer->foreignKey
            );

            //related column same type of owner column

            $foreignKeyDescriber = (new Collection($ownerTableDescriber->foreignKeys))
                ->filter(fn(ForeignKeyDescriber $foreignKeyDescriber) =>
                    $foreignKeyDescriber->colum === $ownerDescriber->describer->foreignKey &&
                    $foreignKeyDescriber->destination_table === $relatedTableDescriber->name)->first()
                ?? new ForeignKeyDescriber('MISSING',$ownerDescriber->describer->foreignKey, $ownerTableDescriber->name,'' );

            $owner_type = (((new Collection($ownerTableDescriber->columns??[]))
                ->filter(fn(ColumnDescriber $columnDescriber) =>
                $foreignKeyDescriber->destination_colum)->first())
                ?? new ColumnDescriber( $foreignKeyDescriber->destination_colum,'NONE',false)
            )->type;

            $related_type = (((new Collection($ownerTableDescriber->columns??[]))
                ->filter(fn(ColumnDescriber $columnDescriber) =>
                $foreignKeyDescriber->colum)->first())
                ?? new ColumnDescriber( $foreignKeyDescriber->destination_colum,'NONE_RELATED',false)
            )->type;


            if (
                $foreignKeyDescriber && $owner_type===$related_type
            ){
                $validations[] = new ValidationDescriber(
                    $ownerDescriber,
                    true,
                    ValidationResultEnum::INFO,
                    $ownerTableDescriber->name .'->'.$foreignKeyDescriber->colum.' ['.$owner_type.'] type coherent with '.$relatedTableDescriber->name. "->".$foreignKeyDescriber->destination_colum.' ['. $related_type.']'
                );
            }else{
                $validations[] = new ValidationDescriber(
                    $ownerDescriber,
                    false,
                    ValidationResultEnum::ERROR,
                    $ownerTableDescriber->name .'->'.$foreignKeyDescriber->colum.' ['.$owner_type.'] type mismatch with '.$relatedTableDescriber->name. "->".$foreignKeyDescriber->destination_colum.' ['. $related_type.']'          );
            }

        }else{
            $validations[] = new ValidationDescriber(
                $ownerDescriber,
                false,
                ValidationResultEnum::ERROR,
                $ownerTableDescriber->name .' table hasn\'t foreignKey on ' . $ownerDescriber->describer->foreignKey
            );
        }

        return $validations;
    }
}