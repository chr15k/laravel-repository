<?php

return [

    'paths' => [

        // Set the path to your models (app_path() is Laravel default)
        'models' => app_path(),

        // Set the Repository class path.
        'repo'  => app_path('Repositories/Eloquent'),

        // Set the Repository interface path.
        'repo_interface' => app_path('Repositories/Contracts')

    ]

];
