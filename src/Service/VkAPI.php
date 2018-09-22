<?php

#    env(VK_URL): ''
#    env(VK_CLIENT): ''
#    env(VK_SECRET): ''
#    env(VK_OAUTH_URL): ''

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VkAPI
{

    const VK_REDIRECT_URL  = 'http://127.0.0.1:8001/vk/code';

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * VkAPI constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct( SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function authorize() : string
    {
        $oauth = new VKOAuth();
        $client_id = 6699413;
        $redirect_uri = self::VK_REDIRECT_URL;
        $display = VKOAuthDisplay::PAGE;
        $scope = [VKOAuthUserScope::FRIENDS, VKOAuthUserScope::PHOTOS, VKOAuthUserScope::VIDEO];
        $state = 'secret_state_code';

        $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE,
            $client_id, $redirect_uri, $display, $scope, $state);

        return $browser_url;
    }

    /**
     * @param $code
     *
     * @return string
     * @throws \VK\Exceptions\VKClientException
     * @throws \VK\Exceptions\VKOAuthException
     */
    public function getToken(string $code) : string
    {
        $oauth = new VKOAuth();
        $client_id = 6699413;
        $client_secret = 'nhoRcQSIyzt4KwROC6B0';
        $redirect_uri = self::VK_REDIRECT_URL;

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);

        return $access_token = $response['access_token'];
    }

    /**
     * @return mixed
     *
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    public function getFriends()
    {
        $vk = new VKApiClient();
        $token = $this->session->get('vk_token');

        if (!$token) return false;

        return $response = $vk->friends()->get($token);
    }
}