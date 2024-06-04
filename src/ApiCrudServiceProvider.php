<?php

namespace Laraflow\ApiCrud;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ApiCrudServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/api-crud.php', 'api-crud'
        );
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/api-crud.php' => config_path('api-crud.php'),
        ], 'api-crud-config');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'api-crud');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/api-crud'),
        ], 'api-crud-lang');

        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/api-crud'),
        ], 'api-crud-stubs');

        $this->loadCommands();
    }

    /**
     * Register all commands on System.
     */
    private function loadCommands(): void
    {
        if ($this->app->runningInConsole() && Config::get('api-crud.enabled', false)) {
            /**
             * As registration is done conditional
             * Using full namespace and runtime call.
             */
            $this->commands([
                \Laraflow\ApiCrud\Commands\InstallCommand::class,
                \Laraflow\ApiCrud\Commands\ControllerMakeCommand::class,
                \Laraflow\ApiCrud\Commands\FactoryMakeCommand::class,
                \Laraflow\ApiCrud\Commands\MigrationMakeCommand::class,
                \Laraflow\ApiCrud\Commands\ModelMakeCommand::class,
                \Laraflow\ApiCrud\Commands\PolicyMakeCommand::class,
                \Laraflow\ApiCrud\Commands\RequestMakeCommand::class,
                \Laraflow\ApiCrud\Commands\ResourceMakeCommand::class,
                \Laraflow\ApiCrud\Commands\SeedMakeCommand::class,
                \Laraflow\ApiCrud\Commands\TestMakeCommand::class,
                \Laraflow\ApiCrud\Commands\CrudMakeCommand::class,
            ]);
        }
    }
}
