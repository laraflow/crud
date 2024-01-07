<?php

namespace Laraflow\Crud;

use Illuminate\Support\ServiceProvider;
use Laraflow\Crud\Commands\InstallCommand;
use Laraflow\Crud\Commands\CrudCommand;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/crud.php', 'crud'
        );
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'crud');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'crud');

        $this->loadPublishOptions();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CrudCommand::class,
            ]);
        }
    }

    private function loadPublishOptions(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/crud'),
        ], 'crud-view');

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/crud'),
        ], 'crud-lang');

        $this->publishes([
            __DIR__ . '/../config/crud.php' => config_path('crud.php'),
        ], 'crud-config');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/crud'),
        ], 'crud-asset');
    }
}
