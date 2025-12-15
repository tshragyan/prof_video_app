<?php

return [
    'api_id' => env('TELEGRAM_API_ID'),
    'api_hash' => env('TELEGRAM_API_HASH'),
    'session' => storage_path('app/telegram/session.madeline'),
    'error_bot_token' => env('TELEGRAM_ERROR_BOT_TOKEN'),
    'error_bot_chat_id' => env('TELEGRAM_ERROR_BOT_CHAT_ID')
];
