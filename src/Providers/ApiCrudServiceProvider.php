<?php

namespace Laraflow\ApiCrud\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laraflow\ApiCrud\Commands\ControllerMakeCommand;
use Laraflow\ApiCrud\Commands\CrudMakeCommand;
use Laraflow\ApiCrud\Commands\InstallCommand;
use Laraflow\ApiCrud\Commands\MigrationMakeCommand;
use Laraflow\ApiCrud\Commands\ModelMakeCommand;
use Laraflow\ApiCrud\Commands\RequestMakeCommand;
use Laraflow\ApiCrud\Commands\ResourceMakeCommand;

class ApiCrudServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/api-crud.php', 'api-crud'
        );

        $this->app->register(MacroServiceProvider::class);
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/api-crud.php' => config_path('api-crud.php'),
        ], 'api-crud-config');

        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'api-crud');

        $this->publishes([
            __DIR__.'/../../lang' => $this->app->langPath('vendor/api-crud'),
        ], 'api-crud-lang');

        $this->publishes([
            __DIR__.'/../../stubs' => base_path('stubs/api-crud'),
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
                InstallCommand::class,
                ControllerMakeCommand::class,
                MigrationMakeCommand::class,
                ModelMakeCommand::class,
                RequestMakeCommand::class,
                ResourceMakeCommand::class,
                CrudMakeCommand::class,
            ]);
        }
    }
}
