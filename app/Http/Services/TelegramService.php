<?php

namespace App\Http\Services;

use danog\MadelineProto\API;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TelegramService
{
    protected API $client;
    protected string $sessionPath = 'telegram/session.madeline';

    const INSTAGRAM_VIDEO_DOWNLOADER_BOT_1 = '@vinsteBot';
    const INSTAGRAM_VIDEO_DOWNLOADER_BOT_2 = '@instadowlbot';
    const INSTAGRAM_VIDEO_DOWNLOADER_BOT_3 = '@VideoAsBot';

    const INSTAGRAM_VIDEO_DOWNLOADER_BOTS_LIST = [
        self::INSTAGRAM_VIDEO_DOWNLOADER_BOT_1,
        self::INSTAGRAM_VIDEO_DOWNLOADER_BOT_2,
        self::INSTAGRAM_VIDEO_DOWNLOADER_BOT_3,
    ];

    public function __construct()
    {
        $settings = (new \danog\MadelineProto\Settings\AppInfo)
            ->setApiId(config('telegram.api_id'))
            ->setApiHash(config('telegram.api_hash'));
        $this->client = new API(storage_path('app\telegram\session.madeline'), $settings);
        $this->client->start();
    }

    public function sendMessage(string $text): array|null
    {
        $data = null;

        foreach (self::INSTAGRAM_VIDEO_DOWNLOADER_BOTS_LIST as $bot) {
            $message = $this->client->messages->sendMessage(peer: $bot, message: $text);
            $id = $message['updates'][0]['id'];

            if (!is_null($id)) {
                $data = [
                    'id' => $id,
                    'bot' => $bot
                ];
                break;
            }
        }

        return $data;
    }

    public function getBotMessageVideoById(string $bot, int $id)
    {
        $response = $this->client->messages->getHistory(
            peer: $bot,
            min_id: $id,
            limit: 2
        );

        $path = storage_path('app/public/telegram_bot/videos');
        Storage::disk('public')->path('telegram_bot/videos');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $path = $this->client->downloadToDir($response['messages'][0]['media'], $path);


        return ['path' => $path, 'size' => $response['messages'][0]['media']['document']['size']];
    }

    public function getSelf()
    {
        return $this->client->getSelf();
    }

    /** Получение новых сообщений от бота */
    public function getBotMessages(string $botUsername): array
    {
        $updates = $this->client->getUpdates(['offset' => 0, 'limit' => 50, 'timeout' => 0]);

        $botId = $this->client->getPwrChat($botUsername)['id'];

        $messages = [];

        foreach ($updates as $update) {
            if (!isset($update['update']['message'])) continue;

            $msg = $update['update']['message'];

            if (($msg['peer_id']['user_id'] ?? null) != $botId) continue;

            $messages[] = $msg;
        }

        return $messages;
    }

    /** Скачать видео если есть */
    public function downloadVideoIfExists($message)
    {
        if (!isset($message['media']['document'])) return null;

        $path = "videos/video_" . time() . ".mp4";
        $fullPath = storage_path("app/" . $path);

        $this->client->downloadToFile($message, $fullPath);

        return $path;
    }
}
