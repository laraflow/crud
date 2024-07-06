<?php

namespace Laraflow\Crud\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laraflow\Crud\Commands\ControllerMakeCommand;
use Laraflow\Crud\Commands\CrudMakeCommand;
use Laraflow\Crud\Commands\InstallCommand;
use Laraflow\Crud\Commands\MigrationMakeCommand;
use Laraflow\Crud\Commands\ModelMakeCommand;
use Laraflow\Crud\Commands\RequestMakeCommand;
use Laraflow\Crud\Commands\ResourceMakeCommand;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/crud.php', 'crud'
        );

        $this->app->register(MacroServiceProvider::class);
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/crud.php' => config_path('crud.php'),
        ], 'crud-config');

        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'crud');

        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/crud'),
        ], 'crud-lang');

        $this->publishes([
            __DIR__ . '/../../stubs' => base_path('stubs/crud'),
        ], 'crud-stubs');

        $this->loadCommands();
    }

    /**
     * Register all commands on System.
     */
    private function loadCommands(): void
    {
        if ($this->app->runningInConsole() && Config::get('crud.enabled', false)) {
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
