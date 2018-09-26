<?php

#    env(VK_URL): ''
#    env(VK_CLIENT): ''
#    env(VK_SECRET): ''
#    env(VK_OAUTH_URL): ''

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiAuthException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;
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

    /**
     * @param $type
     * @return mixed
     *
     */
    public function getData($type)
    {
        switch ($type) {
            case "friend":
                $response = $this->getFriend();
                break;
            case "photo":
                $response = $this->getPhoto();
                break;
            case "video":
                $response = $this->getVideo();
                break;
            default:
                $response = null;
            }

        return $response;
    }

    public function getFriend()
    {
        $vk = new VKApiClient();
        $token = $this->session->get('vk_token');

        if (!$token) return false;
        try {
            $userID = $vk->friends()->get($token)["items"][0];
            $response = $vk->users()->get($token, ['user_ids' => $userID]);
        } catch (VKApiAuthException $e)
        {
            $this->session->set('vk_token', null);
            $response = false;
        } catch (VKApiException $e) {
        } catch (VKClientException $e) {
        }

        return $response;
    }

    public function getPhoto()
    {
        $vk = new VKApiClient();
        $token = $this->session->get('vk_token');

        if (!$token) return false;
        try {
            $response = $vk->photos()->getAll($token)["items"][0]["sizes"][0]["url"];
        } catch (VKApiAuthException $e)
        {
            $this->session->set('vk_token', null);
            $response = false;
        } catch (VKApiException $e) {
        } catch (VKClientException $e) {
        }

        return $response;
    }

    public function getVideo()
    {
        $vk = new VKApiClient();
        $token = $this->session->get('vk_token');

        if (!$token) return false;
        try {
            $response = $vk->video()->get($token)["items"][0]["player"];
        } catch (VKApiAuthException $e)
        {
            $this->session->set('vk_token', null);
            $response = false;
        } catch (VKApiException $e) {
        } catch (VKClientException $e) {
        }

        return $response;
    }
}