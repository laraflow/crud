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
            __DIR__.'/../config/crud.php', 'fintech.crud'
        );

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/crud.php' => config_path('fintech/crud.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'crud');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/crud'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'crud');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/crud'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CrudCommand::class,
            ]);
        }
    }
}
