<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\Http;

class TelegramLogger
{
    public static function send(string $text): void
    {
        if (env('APP_ENV') != 'local') {
            Http::post(
                'https://api.telegram.org/bot' . config('telegram.error_bot_token') . '/sendMessage',
                [
                    'chat_id' => config('telegram.error_bot_chat_id'),
                    'text' => mb_substr($text, 0, 4000),
                    'parse_mode' => 'HTML',
                ]
            );
        }
    }
}
