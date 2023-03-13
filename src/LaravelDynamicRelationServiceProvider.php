<?php

namespace Gianfriaur\LaravelDynamicRelation;

use Gianfriaur\LaravelDynamicRelation\ServiceProvider\ServicesProvider;
use Illuminate\Support\ServiceProvider;

class LaravelDynamicRelationServiceProvider extends ServiceProvider
{

    const CONFIG_NAMESPACE = "laravel_dynamic_relation";
    const CONFIG_FILE_NANE = "laravel_dynamic_relation.php";

    public function boot(): void
    {
        $this->bootConfig();
    }


    public function register(): void
    {
        $this->registerConfig();

        $this->app->register(ServicesProvider::class);
    }

    private function bootConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/' . self::CONFIG_FILE_NANE => config_path(self::CONFIG_FILE_NANE),
        ]);
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/' . self::CONFIG_FILE_NANE, self::CONFIG_NAMESPACE
        );
    }

}