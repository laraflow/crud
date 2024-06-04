<?php

/*
|--------------------------------------------------------------------------
|  Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used during authentication for various
| messages that we need to display to the user. You are free to modify
| these language lines according to your application's requirements.
|
*/

return [
    'resource' => [
        'created' => ':model created successfully.',
        'notfound' => 'No :model found with ID :id.',
        'updated' => ':model updated successfully.',
        'deleted' => ':model deleted successfully.',
        'restored' => ':model restored successfully.',
        'exported' => ':model exported successfully.',
    ],
    'exception' => [
        'store' => 'There\'s been an error. :model might not have been saved.',
        'update' => 'There\'s been an error. :model with ID::id might not have been updated.',
        'delete' => 'There\'s been an error. :model with ID::id might not have been deleted.',
        'restore' => 'There\'s been an error. :model with ID::id might not have been restored.',
        'default' => 'There\'s been an error. Please try again later.',
    ],
    'action' => [
        'show' => 'Preview',
        'update' => 'Edit',
        'destroy' => 'Delete',
        'restore' => 'Restore',
    ],
];
