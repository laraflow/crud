<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Accessibility
    |--------------------------------------------------------------------------
    | This setting will disable command on production server.
    */
    'enabled' => env('APP_ENV', 'production') == 'local',
];
