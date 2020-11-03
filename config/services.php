<?php

return [
    'google' => [
        'client_id' => env('GOOGLE_ID'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect' => env('APP_URL') . 'login/google/callback',
    ],
    'twilio' => [
        'TWILIO_AUTH_TOKEN'  => env('TWILIO_AUTH_TOKEN'),
        'TWILIO_ACCOUNT_SID' => env('TWILIO_ACCOUNT_SID'),
        'TWILIO_PHONE_NUMBER' => env('TWILIO_PHONE_NUMBER')
    ]
];
