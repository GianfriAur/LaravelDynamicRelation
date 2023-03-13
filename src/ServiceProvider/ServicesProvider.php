<?php

namespace Gianfriaur\LaravelDynamicRelation\ServiceProvider;

use Gianfriaur\LaravelDynamicRelation\Exception\DynamicRelationBadServiceDefinitionException;
use Gianfriaur\LaravelDynamicRelation\Exception\DynamicRelationMissingConfigException;
use Gianfriaur\LaravelDynamicRelation\LaravelDynamicRelationServiceProvider;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService\DynamicRelationBuilderServiceInterface;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServicesProvider extends ServiceProvider
{
    /**
     * @throws DynamicRelationBadServiceDefinitionException
     * @throws DynamicRelationMissingConfigException
     */
    public function register(): void
    {
        $this->registerBuilderService();
        $this->registerRegisterService();
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

}