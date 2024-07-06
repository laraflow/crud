<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Accessibility
    |--------------------------------------------------------------------------
    | This setting will disable command on production server.
    */
    'enabled' => env('APP_ENV', 'production') == 'local',

    /*
    |--------------------------------------------------------------------------
    | Parent Controller Namespace
    |--------------------------------------------------------------------------
    | This setting will be used to set which controller class that all api
    | controller will inherit.
    */
    'parent_controller' => \App\Http\Controllers\Controller::class,

    /*
    |--------------------------------------------------------------------------
    | API Route File
    |--------------------------------------------------------------------------
    |
    | Location where the API route file resides.
    | Note: Relative to root directory
    |
    */
    'route_path' => 'routes/api.php',

    /*
    |--------------------------------------------------------------------------
    | Root Namespace
    |--------------------------------------------------------------------------
    |
    | Default root namespace.
    |
    */
    'namespace' => 'App',

    /*
    |--------------------------------------------------------------------------
    | Root Namespace Path
    |--------------------------------------------------------------------------
    |
    | Default root namespace source path.
    | Note: Relative to root directory
    |
    */
    'root_path' => 'app',

    /*
    |--------------------------------------------------------------------------
    | Use Simple Message
    |--------------------------------------------------------------------------
    | This setting will return the actual debug error messages with simple
    | failed message
    */
    'simple_message' => true,

    /*
    |--------------------------------------------------------------------------
    | Template Settings
    |--------------------------------------------------------------------------
    | Customise the paths where the folders will be generated.
    | Set the generate key too false to not generate that folder
    | Note: migration, tests are excluded from root path
    |
    */
    'templates' => [
        'migration' => [
            'path' => 'database/migrations',
            'generate' => true,
            'namespace' => null,
        ],
        'seeder' => [
            'path' => 'database/seeders',
            'generate' => true,
            'namespace' => 'Database\Seeders',
        ],
        'factory' => [
            'path' => 'database/Factories',
            'generate' => false,
            'namespace' => 'Database\Factories',
        ],
        'model' => [
            'path' => 'Models',
            'generate' => true,
            'namespace' => 'Models',
        ],
        'controller' => [
            'path' => 'Http/Controllers',
            'generate' => true,
            'namespace' => 'Http\Controllers',
        ],
        'request' => [
            'path' => 'Http/Requests',
            'generate' => true,
            'namespace' => 'Http\Requests',
        ],
        'resource' => [
            'path' => 'Http/Resources',
            'generate' => true,
            'namespace' => 'Http\Resources',
        ],
        'policies' => [
            'path' => 'Policies',
            'generate' => false,
            'namespace' => 'Policies',
        ],
        'unit-test' => [
            'path' => 'tests/Unit',
            'generate' => true,
            'namespace' => 'Tests\Unit',
        ],
        'feature-test' => [
            'path' => 'tests/Feature',
            'generate' => true,
            'namespace' => 'Tests\Feature',
        ],
    ],
];
