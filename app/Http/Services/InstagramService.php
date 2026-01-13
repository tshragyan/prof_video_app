<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class InstagramService
{
    Public function importReels($videoId)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Instagram 302.1.0.34.111 Android',
            'X-IG-App-ID' => '936619743392459',
            'X-Requested-With' => 'XMLHttpRequest',
            'Referer' => 'https://www.instagram.com/',
            'Accept' => '*/*'
        ])->withCookies([
            'sessionid' => '78018370422%3AdqW7IuumjKIMh2%3A7%3AAYgHao3UtSslU0-XvmzXGvXefhCtM1WR-lRVw2YNpw',
            'csrftoken' => 'mxhkkD0Qk0ERRa5Dg2h4WxFaPBgsQ3D9',
            'ds_user_id' => 78018370422
        ], '.instagram.com')
            ->get("https://www.instagram.com/api/v1/media/{$videoId}/info/");

        $data = $response->json();
        $videoUrl = $data['items'][0]['video_versions'][0]['url'];
        $path = storage_path("app/public/instagram/reels");

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $bytes = filesize($path);
        $mb = round($bytes / 1024 / 1024, 2);

        file_put_contents(
            "$path/{$videoId}.mp4",
            file_get_contents($videoUrl)
        );

        return ['path' => $path, 'size' => $mb];
    }
}
