<?php

return [
    'doctor_count' => env('DOCTOR_SEED_COUNT', 10),

    'super_admin' => [
        'first_name' => env('SUPER_ADMIN_FIRST_NAME'),
        'last_name' => env('SUPER_ADMIN_LAST_NAME'),
        'phone' => env('SUPER_ADMIN_PHONE'),
        'email' => env('SUPER_ADMIN_EMAIL'),
        'password' => env('SUPER_ADMIN_PASSWORD'),
    ],
];
