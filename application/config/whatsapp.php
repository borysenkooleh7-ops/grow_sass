<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your WhatsApp API settings here. This CRM uses WATI as the
    | single WhatsApp provider. Multi-provider support has been disabled.
    |
    | Provider: WATI WhatsApp Team Inbox (https://wati.io)
    | Connection management is handled automatically via WATI API token.
    |
    */

    'provider' => env('WHATSAPP_PROVIDER', 'wati'),

    // Multi-connection feature disabled for single WATI token mode
    'multi_connection_enabled' => env('WHATSAPP_MULTI_CONNECTION_ENABLED', false),

    // Connection management UI disabled
    'connection_management_enabled' => env('WHATSAPP_CONNECTION_MANAGEMENT_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | WATI Settings (Primary Provider)
    |--------------------------------------------------------------------------
    | Get token from: https://app.wati.io/dashboard/api
    | This is the only WhatsApp provider configured for this system.
    */

    'wati' => [
        'api_endpoint' => env('WATI_API_ENDPOINT', 'https://app-server.wati.io'),
        'access_token' => env('WATI_ACCESS_TOKEN', ''),
        'api_version' => env('WATI_API_VERSION', 'v1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Connection Settings
    |--------------------------------------------------------------------------
    */

    'connection' => [
        'timeout' => env('WHATSAPP_TIMEOUT', 30),
        'retry_times' => env('WHATSAPP_RETRY_TIMES', 3),
        'retry_delay' => env('WHATSAPP_RETRY_DELAY', 1000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Settings
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'max_length' => 4096,
        'max_media_size' => 16 * 1024 * 1024, // 16MB
        'allowed_media_types' => [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'video' => ['mp4', '3gp', 'mov', 'avi'],
            'audio' => ['mp3', 'ogg', 'opus', 'aac', 'wav'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip'],
        ],
        'rate_limit' => [
            'enabled' => true,
            'messages_per_minute' => 60,
            'messages_per_hour' => 1000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcast Settings
    |--------------------------------------------------------------------------
    */

    'broadcast' => [
        'batch_size' => 100, // Number of messages to send per batch
        'delay_between_batches' => 5, // seconds
        'delay_between_messages' => 500, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Settings
    |--------------------------------------------------------------------------
    */

    'webhook' => [
        'verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'your-verify-token'),
        'secret' => env('WHATSAPP_WEBHOOK_SECRET', 'your-webhook-secret'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Settings
    |--------------------------------------------------------------------------
    */

    'queue' => [
        'enabled' => env('WHATSAPP_QUEUE_ENABLED', true),
        'connection' => env('WHATSAPP_QUEUE_CONNECTION', 'redis'),
        'queue' => env('WHATSAPP_QUEUE_NAME', 'whatsapp'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SLA Settings
    |--------------------------------------------------------------------------
    */

    'sla' => [
        'update_interval' => 5, // minutes - how often to check SLA status
        'default_first_response' => 30, // minutes
        'default_resolution' => 24, // hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Hours
    |--------------------------------------------------------------------------
    */

    'business_hours' => [
        'monday' => ['open' => '09:00', 'close' => '18:00', 'is_closed' => false],
        'tuesday' => ['open' => '09:00', 'close' => '18:00', 'is_closed' => false],
        'wednesday' => ['open' => '09:00', 'close' => '18:00', 'is_closed' => false],
        'thursday' => ['open' => '09:00', 'close' => '18:00', 'is_closed' => false],
        'friday' => ['open' => '09:00', 'close' => '18:00', 'is_closed' => false],
        'saturday' => ['open' => '10:00', 'close' => '14:00', 'is_closed' => false],
        'sunday' => ['open' => '00:00', 'close' => '00:00', 'is_closed' => true],
    ],

    /*
    |--------------------------------------------------------------------------
    | Chatbot Settings
    |--------------------------------------------------------------------------
    */

    'chatbot' => [
        'enabled' => true,
        'session_timeout' => 30, // minutes
        'max_retries' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Settings
    |--------------------------------------------------------------------------
    */

    'storage' => [
        'media_path' => 'whatsapp/media',
        'temp_path' => 'whatsapp/temp',
        'disk' => env('WHATSAPP_STORAGE_DISK', 'public'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */

    'features' => [
        'automation' => env('WHATSAPP_AUTOMATION_ENABLED', true),
        'routing' => env('WHATSAPP_ROUTING_ENABLED', true),
        'sla' => env('WHATSAPP_SLA_ENABLED', true),
        'chatbot' => env('WHATSAPP_CHATBOT_ENABLED', true),
        'broadcasts' => env('WHATSAPP_BROADCASTS_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => env('WHATSAPP_LOGGING_ENABLED', true),
        'channel' => env('WHATSAPP_LOG_CHANNEL', 'daily'),
        'level' => env('WHATSAPP_LOG_LEVEL', 'info'),
    ],

];
