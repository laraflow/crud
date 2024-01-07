<?php

namespace VendorName\Skeleton\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VendorName\Skeleton\SkeletonServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends Orchestra
{
    
    use DatabaseMigrations;

    
    /** 
    * @return void 
    */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** 
    * @param $app 
    * @return string[] 
    */
    protected function getPackageProviders($app): array
    {
        return [
            SkeletonServiceProvider::class,
        ];
    }
    
    /** 
    * @param $app 
    * @return void 
    */
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
