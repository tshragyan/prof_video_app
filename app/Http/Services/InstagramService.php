<?php


namespace App\Http\Services;


use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class InstagramService
{

    const INSTAGRAM_API_URL = 'https://www.instagram.com/api/v1';

    private function sendRequest($url)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Instagram 302.1.0.34.111 Android',
            'X-IG-App-ID' => '936619743392459',
            'X-Requested-With' => 'XMLHttpRequest',
            'Referer' => 'https://www.instagram.com/',
            'Accept' => '*/*'
        ])->withCookies([
            'sessionid' => config('services.instagram.sessionid'),
            'csrftoken' => config('services.instagram.csrftoken'),
            'ds_user_id' => config('services.instagram.ds_user_id')
        ], '.instagram.com')
            ->get(self::INSTAGRAM_API_URL . $url);

        return $response->json();
    }

    public function importReels($videoId)
    {
        $data = $this->sendRequest("/media/{$videoId}/info/");
        $videoUrl = $data['items'][0]['video_versions'][0]['url'];

        return ['path' => "$videoUrl", 'ig_video_id' => $videoId];
    }

    public function findUserIdByUsername($username): int|null
    {
        $data = $this->sendRequest("/users/web_profile_info/?username={$username}");
        return is_null($data) ? null : $data['data']['user']['id'];
    }

    /** @todo add scraping videos from all pages not only from first one */
    public function getVideosFromUserPageById(int $userId, ?string $maxId = null)
    {
//        if (Cache::get('videos')) {
//            return json_decode(Cache::get('videos'), true);
//        }

        $videos = [];
        $queryData = [
            'target_user_id' => $userId,
//            'max_id' => 'QVFBQlVuRFc5SWxkdlNXeHQ3azB2cDlPeUFyNXhkV2tuelZXVjl0WG1XajQyZWVSdFBGODFMaVlMV1ZTcEFUUDhCZDJYT3N0OEUwQkNXeVpRbVhYTkVTWg=='
        ];

        if (!is_null($maxId)) {
            $queryData['after'] = $maxId;
        }

        $next = null;

//        do {
//            if ($next) $url .= "?max_id={$next}";
        $response = Http::withHeaders([
            'User-Agent' => 'Instagram 302.1.0.34.111 Android',
            'X-IG-App-ID' => '936619743392459',
            'X-Requested-With' => 'XMLHttpRequest',
            'Referer' => 'https://www.instagram.com/',
            'Accept' => '*/*',
        ])->withCookies([
            'sessionid' => config('services.instagram.sessionid'),
            'csrftoken' => config('services.instagram.csrftoken'),
            'ds_user_id' => config('services.instagram.ds_user_id'),
        ], '.instagram.com')
            ->asForm()
            ->post('https://i.instagram.com/api/v1/clips/user/', $queryData);
        $data = $response->json();

        foreach ($data['items'] as $item) {
            if (isset($item['media']['video_versions'])) {
                $videos['data'][] = [
                    'id' => $item['media']['id'],
                    'url' => $item['media']['video_versions'][0]['url'],
                    'title' => $item['media']['caption']['text'],
                    'thumbnail' => $item['media']['image_versions2']['candidates'][0]['url']
                ];
            }
        }

        if ($data['paging_info']['more_available']) {
            $videos['next_page_id'] = $data['paging_info']['max_id'];
            $videos['available_more'] = $data['paging_info']['more_available'];
        }

        Cache::put('videos', json_encode($videos));

//            $next = $json['next_max_id'] ?? null;
//
//        } while ($next);

        return $videos;
    }
}
