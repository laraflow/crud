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
        'created' => 'The :model item created successfully.',
        'saved' => 'The :model item saved successfully.',
        'notfound' => 'No :model found with ID :id.',
        'updated' => 'The :model item updated successfully.',
        'deleted' => 'The :model item deleted successfully.',
        'restored' => 'The :model item restored successfully.',
        'exported' => 'The :model item exported successfully.',
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
