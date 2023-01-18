<?php

return [
    // Version of ApiController you want to develop (v1, v2, v3, null)
    'version' => 'v1',

    // ID type you want to use (id, uuid, ulid)
    'id-type' => 'ulid',

    // Enable or disable sorting in migration and model
    'sorting' => true,

    // Per page pagination default
    'per_page' => 20,

    // File that 'php artisan wame:make' command should make
    'make' => [
        'model' => true,
        'migration' => true,
        'observer' => true,
        'events' => true,
        'listeners' => true,
        'api-controllers' => true,
    ],
];
