<?php

return [
    'db' => [
        'dsn' => 'mysql:dbname=docker-project;host=mysql;port=3306',
        'user' => 'root',
        'password' => 'docker',
    ],
    'route' => [
        [
            'path' => 'login',
            'action' => [\App\Controller\UserController::class, 'login'],
        ],
        [
            'path' => 'logout',
            'action' => [\App\Controller\UserController::class, 'logout'],
        ],
        [
            'path' => 'create',
            'action' => [\App\Controller\TaskController::class, 'create'],
        ],
        [
            'path' => ['update', 'id'], // url: /update/{id}
            'action' => [\App\Controller\TaskController::class, 'update'],
        ],
        [
            'path' => '/',
            'action' => [\App\Controller\TaskController::class, 'index'],
        ],
    ]
];
