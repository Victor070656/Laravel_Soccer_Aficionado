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

    'football_api' => [
        'key' => env('FOOTBALL_API_KEY'),
        'base_url' => env('FOOTBALL_API_URL', 'https://v3.football.api-sports.io'),
        'cache_ttl' => [
            'live' => (int) env('FOOTBALL_API_CACHE_LIVE', 60),          // 1 minute for live
            'fixtures' => (int) env('FOOTBALL_API_CACHE_FIXTURES', 900), // 15 minutes for fixture lists
            'fixture' => (int) env('FOOTBALL_API_CACHE_FIXTURE', 300),   // 5 minutes for single fixture
            'events' => (int) env('FOOTBALL_API_CACHE_EVENTS', 120),     // 2 minutes for events
            'lineups' => (int) env('FOOTBALL_API_CACHE_LINEUPS', 600),   // 10 minutes for lineups
            'statistics' => (int) env('FOOTBALL_API_CACHE_STATS', 300),  // 5 minutes for statistics
        ],
        // Top league IDs on API-Football
        'leagues' => array_map('intval', explode(',', env('FOOTBALL_API_LEAGUES', '39,140,135,78,61'))),
        'season' => (int) env('FOOTBALL_API_SEASON', date('Y')),
    ],

];
