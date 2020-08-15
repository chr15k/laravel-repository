<?php

return [

    'paths' => [

        // Set the path to your models (app_path() is Laravel default)
        // --> app_path()
        // --> app/User
        'models' => app_path(),

        // Set the Repository concrete class path.
        // --> app_path('Repositories')
        // --> app/Repositories/UserRepository
        'repo'  => app_path('Repositories/Eloquent'),

        // Set the Repository interface path.
        // --> app_path('Repositories/Contracts')
        // --> app/Repositories/Contracts/UserRepositoryInterface
        'repo_interface' => app_path('Repositories/Contracts')

    ]

];
