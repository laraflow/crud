<?php

// config for Laraflow/Crud
return [
    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */

    'namespace' => 'App',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */

    'stubs' => [
        'enabled' => false,
        'path' => base_path('vendor/laraflow/crud/src/Commands/stubs'),
        'files' => [
            'routes/web' => 'routes/web.php',
            'routes/api' => 'routes/api.php',
            'views/index' => 'resources/views/index.blade.php',
            'views/master' => 'resources/views/layouts/master.blade.php',
            'scaffold/config' => 'config/config.php',
            //            'composer' => 'composer.json',
            //            'assets/js/app' => 'Resources/assets/js/app.js',
            //            'assets/sass/app' => 'Resources/assets/sass/app.scss',
            //            'vite' => 'vite.config.js',
            //            'package' => 'package.json',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/api' => ['LOWER_NAME'],
            'views/index' => ['LOWER_NAME'],
            'views/master' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            //            'composer' => [
            //                'LOWER_NAME',
            //                'STUDLY_NAME',
            //                'VENDOR',
            //                'AUTHOR_NAME',
            //                'AUTHOR_EMAIL',
            //                'MODULE_NAMESPACE',
            //                'PROVIDER_NAMESPACE',
            //            ],
            //            'vite' => ['LOWER_NAME'],
            //            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
        ],
        'gitkeep' => true,
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will be added
        | automatically to list of scanned folders.
        |
        */

        'modules' => base_path('packages'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Set the generate key to false to not generate that folder
        */
        'generator' => [
            'config' => [
                'path' => 'config',
                'generate' => true,
                'namespace' => null,
            ],
            'migration' => [
                'path' => 'database/migrations',
                'generate' => false,
                'namespace' => null,
            ],
            'lang' => [
                'path' => 'lang',
                'generate' => true,
                'namespace' => '',
            ],
            'routes' => [
                'path' => 'routes',
                'generate' => false,
                'namespace' => null,
            ],
            'assets' => [
                'path' => 'resources/assets',
                'generate' => false,
                'namespace' => null,
            ],
            'views' => [
                'path' => 'resources/views',
                'generate' => true,
                'namespace' => null,
            ],
            'component-view' => [
                'path' => 'resources/views/components',
                'generate' => false,
                'namespace' => null,
            ],
            'command' => [
                'path' => 'src/Commands',
                'generate' => true,
                'namespace' => 'Commands',
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
            'exception' => [
                'path' => 'src/Exceptions',
                'generate' => false,
                'namespace' => 'Exceptions',
            ],
            'filter' => [
                'path' => 'src/Http/Middleware',
                'generate' => false,
                'namespace' => 'Http\Middleware',
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
            'provider' => [
                'path' => 'src/Providers',
                'generate' => true,
                'namespace' => 'Providers',
            ],
            'repository' => [
                'path' => 'src/Repositories',
                'generate' => true,
                'namespace' => 'Repositories',
            ],
            'event' => [
                'path' => 'src/Events',
                'generate' => false,
                'namespace' => 'Events',
            ],
            'listener' => [
                'path' => 'src/Listeners',
                'generate' => false,
                'namespace' => '',
            ],
            'policies' => [
                'path' => 'src/Policies',
                'generate' => false,
                'namespace' => 'Listeners',
            ],
            'rules' => [
                'path' => 'src/Rules',
                'generate' => false,
                'namespace' => 'Rules',
            ],
            'jobs' => [
                'path' => 'src/Jobs',
                'generate' => false,
                'namespace' => 'Jobs',
            ],
            'mail' => [
                'path' => 'src/Mails',
                'generate' => false,
                'namespace' => 'Mails',
            ],
            'notification' => [
                'path' => 'src/Notifications',
                'generate' => false,
                'namespace' => 'Notifications',
            ],
            'service' => [
                'path' => 'src/Services',
                'generate' => false,
                'namespace' => 'Services',
            ],
            'interface' => [
                'path' => 'src/Interfaces',
                'generate' => false,
                'namespace' => 'Interfaces',
            ],
            'component-class' => [
                'path' => 'src/View/Components',
                'generate' => false,
                'namespace' => 'View\Components',
            ],
            'test' => [
                'path' => 'tests/Unit',
                'generate' => true,
                'namespace' => 'Tests\Unit',
            ],
            'test-feature' => [
                'path' => 'tests/Feature',
                'generate' => true,
                'namespace' => 'Tests\Feature',
            ],
        ],
    ],
];
