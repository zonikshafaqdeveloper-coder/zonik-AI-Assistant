<?php

return [

    'elevenlabs' => [
        // Allows a rotated key to take effect immediately without relying on
        // a stale value that may still exist in a local .env file.
        // The primary key is the value maintained in .env. Keep the legacy
        // override only as a fallback so a stale override can never shadow a
        // freshly rotated primary key.
        'api_key' => env('ELEVENLABS_API_KEY', env('ELEVENLABS_API_KEY_OVERRIDE')),
        // Adam: warm, clear male fallback. Production can still override it
        // through ELEVENLABS_VOICE_ID without changing application code.
        'voice_id' => env('ELEVENLABS_VOICE_ID', 'pNInz6obpgDQGcFmaJgB'),
        'fallback_voice_id' => env('ELEVENLABS_FALLBACK_VOICE_ID'),
        'free_fallback_voice_id' => env('ELEVENLABS_FREE_FALLBACK_VOICE_ID', 'pNInz6obpgDQGcFmaJgB'),
        // Prefer quality over latency for spoken Hindi, Hinglish and Marathi.
        'model' => env('ELEVENLABS_MODEL', 'eleven_multilingual_v2'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'razorpay' => [
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'phone_number' => env('TWILIO_PHONE_NUMBER'),
        'test_recipient' => env('TWILIO_TEST_RECIPIENT'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-3.5-flash-lite'),
    ],

    'customer_care' => [
        'phone' => env('CUSTOMER_CARE_PHONE', '+918850268043'),
    ],

    'curl' => [
        'cacert_path' => 'C:\wamp64\bin\php\php8.0.26\cacert.pem',
    ],

];
