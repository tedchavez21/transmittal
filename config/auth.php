<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | This application uses custom authentication with Admin and Officer models.
    | The default Laravel authentication guards are not used.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
];
