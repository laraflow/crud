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
    | Template Settings
    |--------------------------------------------------------------------------
    | Customise the paths where the folders will be generated.
    | Set the generate key too false to not generate that folder
    */
    'templates' => [
        'migration' => [
            'path' => 'database/migrations',
            'generate' => false,
            'namespace' => null,
        ],
        'routes' => [
            'path' => 'routes',
            'generate' => false,
            'namespace' => null,
        ],
        'seeder' => [
            'path' => 'src/Seeders',
            'generate' => true,
            'namespace' => 'Seeders',
        ],
        'factory' => [
            'path' => 'src/Factories',
            'generate' => false,
            'namespace' => 'Factories',
        ],
        'model' => [
            'path' => 'src/Models',
            'generate' => true,
            'namespace' => 'Models',
        ],
        'controller' => [
            'path' => 'src/Http/Controllers',
            'generate' => true,
            'namespace' => 'Http\Controllers',
        ],
        'request' => [
            'path' => 'src/Http/Requests',
            'generate' => true,
            'namespace' => 'Http\Requests',
        ],
        'resource' => [
            'path' => 'src/Http/Resources',
            'generate' => true,
            'namespace' => 'Http\Resources',
        ],
        'policies' => [
            'path' => 'src/Policies',
            'generate' => false,
            'namespace' => 'Listeners',
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
