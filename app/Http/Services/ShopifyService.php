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

    public function getStoreInfo()
    {
        $query = 'query ShopShow {
                      shop {
                        accountOwner {
                          name
                        }
                        alerts {
                          action {
                            title
                            url
                          }
                          description
                        }
                        billingAddress {
                          address1
                          address2
                          city
                          company
                          country
                          countryCodeV2
                          latitude
                          longitude
                          phone
                          province
                          provinceCode
                          zip
                        }
                        checkoutApiSupported
                        contactEmail
                        createdAt
                        email
                        id
                        myshopifyDomain
                        name
                        primaryDomain {
                          host
                          id
                        }
                        url
                      }
                    }';

        return $this->api->graph($query);
    }


}
