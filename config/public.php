<?php

return [
    /*
    |--------------------------------------------------------------------------
    | routes map
    |--------------------------------------------------------------------------
    | this will contains all customized routes in the models.
    |
    */
    'routes' => [
        'index' => 'index',
        'create' => 'create',
        'post' => 'store',
        'show' => 'show',
        'edit' => 'edit',
        'update' => 'update',
        'delete' => 'destroy',
        'force_delete' => 'forceDestroy',
        'export.xlsx' => 'exportXLSX',
        'export.pdf' => 'exportPDF',
        'import' => 'import',
        'active' => 'active',
    ],
];
