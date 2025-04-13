<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users', // Dùng provider cho user
            'cookie' => 'user_session', // Cookie riêng cho user
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins', // Sử dụng provider cho admin
            'cookie' => 'admin_session', // Cookie riêng cho admin
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Cùng model User cho cả user và admin
        ],

        // Nếu cần phân biệt role cho admin
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Cùng model User, nhưng phân biệt bằng cột role
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];


