<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService;

use App\Models\User;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\OneToManyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\TableDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationDescriberInterface;
use Gianfriaur\LaravelDynamicRelation\Exception\DynamicRelationMissingOptionException;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService\DynamicRelationDriverServiceInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator\ValidatorInterface;
use Gianfriaur\PackageLoader\Exception\MissingMigrationStrategyServiceOptionException;
use Illuminate\Foundation\Application;

class DefaultDynamicRelationValidatorService implements DynamicRelationValidatorServiceInterface
{

    public array $tableDescribers = [];
    public ?array $validations = null;

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(
        private readonly Application $app,
        private readonly DynamicRelationRegisterServiceInterface $registerService,
        private readonly DynamicRelationDriverServiceInterface $driverService,
        private readonly array $options
    )
    {
    }

    private function getTableDescriber($class_name):TableDescriber{
        if (!isset($this->tableDescribers[$class_name])){
            $this->tableDescribers[$class_name] = $this->driverService->getTableDescriber((new $class_name)->getTable());
        }

        return $this->tableDescribers[$class_name];
    }


    private function getOption(string $name): mixed
    {
        if (!array_key_exists($name, $this->options)) {
            throw new DynamicRelationMissingOptionException($name, $this);
        }
        return $this->options[$name];
    }

    /**
     * @return array<ValidatorInterface>
     */
    private function getValidators():array{
        if ($this->validations === null){
            $this->validations =[];
            foreach ($this->getOption('validations') as $validation){
                $this->validations[] = new ($validation);
            }
        }

        return $this->validations;
    }

    /**
     * @param FullRelationDescriber $describer
     * @return array<ValidationDescriberInterface>
     */
    public function validateFullRelationDescriber(FullRelationDescriber $describer): array
    {
        $owner_table_describer = $this->getTableDescriber($describer->ownerClass);
        $related_table_describer = $this->getTableDescriber($describer->relatedClass);

        $validations = [];

        foreach ($this->getValidators() as $validator){

            if ($validator->handle($describer,$owner_table_describer,$related_table_describer)) {
                $result =  $validator->validate($describer,$owner_table_describer,$related_table_describer);

                if(is_array($result)){
                    $validations=  array_merge($validations,$result);
                }else{
                    $validations[] = $result;
                }
            }

        }

        return $validations;


        // TODO: Implement ValidateFullRelationDescriber() method.
    }
}