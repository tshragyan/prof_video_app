<?php

return [
    'api_id' => env('TELEGRAM_API_ID'),
    'api_hash' => env('TELEGRAM_API_HASH'),
    'session' => storage_path('app/telegram/session.madeline'),
];
