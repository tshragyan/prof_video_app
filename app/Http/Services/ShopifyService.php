<?php


namespace App\Http\Services;


use App\Models\User;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;

class ShopifyService
{
    private string $appId;
    private BasicShopifyAPI $api;

    public function __construct(User $user)
    {
        $this->appId = config('services.shopify.app_id');
        $options = (new Options())
        ->setVersion('2025-10')
        ->setType(false);
        $this->api = new BasicShopifyAPI($options);
        $session = new Session($user->getDomain(), $user->shopify_token);
        $this->api->setSession($session);
    }


}
