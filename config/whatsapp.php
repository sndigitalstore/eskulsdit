<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Default using Fonnte.com API.
    |
    */

    'api_token' => env('WHATSAPP_API_TOKEN', ''),
    'endpoint' => env('WHATSAPP_ENDPOINT', 'https://api.fonnte.com/send'),
    
    // Set to true to enable real-time notifications
    'enabled' => env('WHATSAPP_ENABLED', false),
];
