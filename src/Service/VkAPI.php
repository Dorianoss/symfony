<?php

#    env(VK_URL): ''
#    env(VK_CLIENT): ''
#    env(VK_SECRET): ''
#    env(VK_OAUTH_URL): ''

namespace App\Service;

use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VkAPI
{

    public function authorize()
    {
        $oauth = new VKOAuth();
        $client_id = 6699413;
        $redirect_uri = 'http://127.0.0.1:8001/home';
        $display = VKOAuthDisplay::PAGE;
        $scope = array(VKOAuthUserScope::FRIENDS, VKOAuthUserScope::PHOTOS, VKOAuthUserScope::VIDEO);
        $state = 'secret_state_code';

        return $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE,
            $client_id, $redirect_uri, $display, $scope, $state);

//        return json_decode($this->client->request('GET', '/method/friends.get')->getBody()->getContents(), true);return json_decode($this->request('GET', '/method/friends.get')->getBody()->getContents(), true);
    }

    public function getToken($code)
    {
        $oauth = new VKOAuth();
        $client_id = 6699413;
        $client_secret = 'nhoRcQSIyzt4KwROC6B0';
        $redirect_uri = 'http://127.0.0.1:8001/home';

$response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
return $access_token = $response['access_token'];
    }

    public function getFriends($access_token)
    {
        $vk = new VKApiClient();
        return $response = $vk->friends()->get($access_token);
    }
}