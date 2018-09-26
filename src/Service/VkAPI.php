<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VkAPI
{

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var int
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $redirectUri;

    /**
     * VkAPI constructor.
     *
     * @param int $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @param SessionInterface $session
     */
    public function __construct( int $clientId, string $clientSecret, string $redirectUri, SessionInterface $session)
    {
        $this->session = $session;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return string
     */
    public function authorize() : string
    {
        $oauth = new VKOAuth();
        $client_id = $this->clientId;
        $redirect_uri = $this->redirectUri;
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
        $client_id = $this->clientId;
        $client_secret = $this->clientSecret;
        $redirect_uri = $this->redirectUri;

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);

        return $access_token = $response['access_token'];
    }
}