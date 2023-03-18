<?php

namespace Gianfriaur\LaravelDynamicRelation\Console\Commands;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\FullRelationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Validation\ValidationResultEnum;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\DynamicRelationValidatorServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateDynamicRelationCommand extends Command
{
    protected $signature = 'dynamic-relation:validate';

    protected $description = 'This command make the validation of all dynamic relation';

    protected DynamicRelationRegisterServiceInterface $registerService;
    protected DynamicRelationValidatorServiceInterface $validatorService;

    public function __construct()
    {
        parent::__construct();
        $this->registerService = app(DynamicRelationRegisterServiceInterface::class);
        $this->validatorService = app(DynamicRelationValidatorServiceInterface::class);
    }

    public function handle():int
    {
        $this->components->info('Check dynamic relations with <fg=green>' . config('laravel_dynamic_relation.relation_driver') . '</> driver');

        $relations = $this->registerService->getRelations();

        $verbose = $this->output->isVerbose();

        if (sizeof($relations)===0){
            $this->components->info('No dynamic relations found');

        }else{
            $this->components->twoColumnDetail('<fg=gray>Namespace\Class</>-><fg=green>relation</>', '<fg=gray>Status</>');
            foreach ($relations as $entity_class => $entity_relations){
                foreach ($entity_relations as $entity_relation_name => $entity_relation_description){

                    /** @var FullRelationDescriber $describer */
                    $describer = $entity_relation_description['describer'];

                    $validations = $this->validatorService->validateFullRelationDescriber($describer);

                    $has_errors = count ((new Collection($validations))->filter(fn(ValidationDescriber $describer) => $describer->result === ValidationResultEnum::ERROR)) > 0;
                    $has_warnings = count ((new Collection($validations))->filter(fn(ValidationDescriber $describer) => $describer->result === ValidationResultEnum::WARNING)) > 0;


                    $this->components->twoColumnDetail('<fg=gray>' .$entity_class . '</>-><fg=green>'.$entity_relation_name.'</> ',
                        $has_errors ? '<fg=red>ERROR</>' : ($has_warnings ? '<fg=yellow>WARNING</>' : '<fg=green>OK</>'));

                    foreach ($validations as $i => $validation){

                        $this->line(
                            string:"    [ $i ] " .
                                ($validation->result === ValidationResultEnum::ERROR ?'<fg=red>[ ERROR ]</>': ($validation->result === ValidationResultEnum::WARNING ?'<fg=yellow>[ WARNING ]</>' : '<fg=green>[ OK ]</>')).
                                ': '.$validation->message,
                            verbosity: $validation->result === ValidationResultEnum::ERROR
                                ? OutputInterface::VERBOSITY_NORMAL
                                : (
                                    $validation->result === ValidationResultEnum::WARNING
                                        ? OutputInterface::VERBOSITY_VERBOSE
                                        : OutputInterface::VERBOSITY_VERY_VERBOSE
                                ));
                    }


                }
            }

            $this->newLine();
        }

        return 0;
    }
}