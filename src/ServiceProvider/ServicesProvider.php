<?php

namespace Gianfriaur\LaravelDynamicRelation\ServiceProvider;

use Gianfriaur\LaravelDynamicRelation\Console\Commands\ValidateDynamicRelationCommand;
use Gianfriaur\LaravelDynamicRelation\Exception\DynamicRelationBadServiceDefinitionException;
use Gianfriaur\LaravelDynamicRelation\Exception\DynamicRelationMissingConfigException;
use Gianfriaur\LaravelDynamicRelation\LaravelDynamicRelationServiceProvider;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService\DynamicRelationBuilderServiceInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\DynamicRelationValidatorServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServicesProvider extends ServiceProvider
{
    protected array $commands = [
        ValidateDynamicRelationCommand::class
    ];

    /**
     * @throws DynamicRelationBadServiceDefinitionException
     * @throws DynamicRelationMissingConfigException
     */
    public function register(): void
    {
        $this->registerBuilderService();
        $this->registerRegisterService();

        $has_validator =  $this->registerValidatorService();

        if ($this->app->runningInConsole()) {
            if ($has_validator){
                $this->registerCommands();
            }
        }
    }

    /**
     * @throws DynamicRelationMissingConfigException
     */
    private function getConfig($name, bool $nullable = false): mixed
    {
        if (!$config = config(LaravelDynamicRelationServiceProvider::CONFIG_NAMESPACE . '.' . $name)) {
            if (!$nullable) throw new DynamicRelationMissingConfigException($name);
        }
        return $config;
    }

    /**
     * @throws DynamicRelationMissingConfigException
     * @throws DynamicRelationBadServiceDefinitionException
     */
    private function getServiceDefinition(string $selector, string $collection, bool $nullable = true): array
    {
        $selector_value = $this->getConfig($selector, $nullable);
        if ($nullable && $selector_value === null) return [null, []];
        $collection_value = $this->getConfig($collection);
        if (!isset($collection_value[$selector_value])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'service is missing in collection ' . $collection);
        if (!isset($collection_value[$selector_value]['class'])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'service not define a class in  ' . $collection);
        if (!isset($collection_value[$selector_value]['options'])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'service not define the options in ' . $collection);
        return [$collection_value[$selector_value]['class'], $collection_value[$selector_value]['options']];
    }

    /**
     * @throws DynamicRelationMissingConfigException
     * @throws DynamicRelationBadServiceDefinitionException
     */
    private function getDriverServiceDefinition(string $selector, string $collection, bool $nullable = true): array
    {
        $selector_value = $this->getConfig($selector, $nullable);
        if ($nullable && $selector_value === null) return [null, []];
        $collection_value = $this->getConfig($collection);

        if (!isset($collection_value['drivers'])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'drivers is missing in collection ' . $collection);
        if (!isset($collection_value['shared_options'])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'shared_options is missing in collection ' . $collection);


        $collection_value_drivers = $collection_value['drivers'];
        $collection_value_shared_options = $collection_value['shared_options'];

        if (!isset($collection_value_drivers[$selector_value])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'service is missing in collection ' . $collection);
        if (!isset($collection_value_drivers[$selector_value]['class'])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'service not define a class in  ' . $collection);
        if (!isset($collection_value_drivers[$selector_value]['options'])) throw new DynamicRelationBadServiceDefinitionException($selector_value, 'service not define the options in ' . $collection);
        return [$collection_value_drivers[$selector_value]['class'], array_merge( $collection_value_shared_options,$collection_value_drivers[$selector_value]['options'])];
    }

    /**
     * @throws DynamicRelationBadServiceDefinitionException
     * @throws DynamicRelationMissingConfigException
     */
    private function registerBuilderService()
    {

        [$service_class, $service_options] = $this->getServiceDefinition('relation_builder', 'relation_builders', false);

        $this->app->singleton(DynamicRelationBuilderServiceInterface::class, function ($app) use ($service_class, $service_options) {
            return new $service_class($app, $service_options);
        });

        $this->app->alias(DynamicRelationBuilderServiceInterface::class, 'dynamic_relation.builder');
    }

    /**
     * @throws DynamicRelationBadServiceDefinitionException
     * @throws DynamicRelationMissingConfigException
     */
    private function registerRegisterService()
    {

        [$service_class, $service_options] = $this->getServiceDefinition('relation_register', 'relation_registers', false);

        $this->app->singleton(DynamicRelationRegisterServiceInterface::class, function ($app) use ($service_class, $service_options) {
            return new $service_class($app, $app->get('dynamic_relation.builder'), $service_options);
        });

        $this->app->alias(DynamicRelationRegisterServiceInterface::class, 'dynamic_relation.register');
    }
    /**
     * @throws DynamicRelationBadServiceDefinitionException
     * @throws DynamicRelationMissingConfigException
     */
    private function registerValidatorService():bool
    {
        [$service_class, $service_options] = $this->getDriverServiceDefinition('relation_validator', 'relation_validators');

        if ($service_class == null) return false;

        $this->app->singleton(DynamicRelationValidatorServiceInterface::class, function ($app) use ($service_class, $service_options) {
            return new $service_class($app, $app->get('dynamic_relation.register'), $service_options);
        });

        $this->app->alias(DynamicRelationValidatorServiceInterface::class, 'dynamic_relation.validator');

        return true;
    }



    private function registerCommands()
    {
        $this->commands(array_values($this->commands));
    }
}