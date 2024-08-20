<?php

use App\Models\Role;

return [
    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        Role::SUPER_ADMIN => [
        ],
        Role::USER => [
        ],
    ],

    'permissions_map' => [
    ]
];
