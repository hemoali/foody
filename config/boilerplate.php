<?php

return [

    'sign_up' => [
        'release_token' => env('SIGN_UP_RELEASE_TOKEN'),
        'validation_rules' => [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|Regex:/([01])/'
        ]
    ],
    'login' => [
        'validation_rules' => [
            'email' => 'required|email',
            'password' => 'required'
        ]
    ],

    'forgot_password' => [
        'validation_rules' => [
            'email' => 'required|email'
        ]
    ],

    'reset_password' => [
        'release_token' => env('PASSWORD_RESET_RELEASE_TOKEN', false),
        'validation_rules' => [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]
    ],

    'restaurant' => [
        'store_validation_rules' => [
            'name' => 'required',
            'desc' => 'required',
            'location' => 'required',
            'link' => 'required',
            'phone_number' => 'required'
        ],
        'search_rules' => [
            'q' => 'required'
        ]
    ],
    'review' => [
        'store_validation_rules' => [
            'text' => 'required',
            'rate' => 'required|Regex:/([012345])/'
        ]
    ],
    'appointment' => [
        'store_validation_rules' => [
            'time' => 'required'
        ]
    ]

];
