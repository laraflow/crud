<?php

namespace Laraflow\ApiCrud\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraflow\ApiCrud\Providers\ApiCrudServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            ApiCrudServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('app.env', 'testing');
        config()->set('database.default', 'testing');

        $migrations = [
        ];
        foreach ($migrations as $migration) {
            $migration->up();
        }
    }
}
