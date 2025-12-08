<?php

return [

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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feedback Hub External Services
    |--------------------------------------------------------------------------
    |
    | Configuration for feedback analysis and import services
    |
    */
    'google' => [
        'api_key' => env('GOOGLE_API_KEY'),
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    ],

    'microsoft' => [
        'tenant_id' => env('MICROSOFT_TENANT_ID'),
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
    ],

    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
    ],

    'ai_services' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        ],
        'azure_cognitive' => [
            'key' => env('AZURE_COGNITIVE_KEY'),
            'endpoint' => env('AZURE_COGNITIVE_ENDPOINT'),
        ],
        'google_nlp' => [
            'credentials_path' => env('GOOGLE_NLP_CREDENTIALS_PATH'),
            'project_id' => env('GOOGLE_NLP_PROJECT_ID'),
        ],
    ],

];
