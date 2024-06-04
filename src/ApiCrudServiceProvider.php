<?php

namespace Laraflow\ApiCrud;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laraflow\ApiCrud\Commands\InstallCommand;

class ApiCrudServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/api-crud.php', 'api-crud'
        );
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/api-crud.php' => config_path('api-crud.php'),
        ], 'api-crud-config');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'api-crud');

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/api-crud'),
        ], 'api-crud-lang');

        $this->publishes([
            __DIR__ . '/../stubs' => base_path('stubs/api-crud'),
        ], 'api-crud-stubs');

        $this->loadCommands();
    }

    /**
     * Register all commands on System.
     *
     * @return void
     */
    private function loadCommands(): void
    {
        if ($this->app->runningInConsole() && Config::get('api-crud.enabled', false)) {
            $this->commands([
                InstallCommand::class
            ]);
        }
    }
}
