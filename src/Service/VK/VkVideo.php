<?php

namespace App\Service\VK;

use App\Service\VK\Type\TypeInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiAuthException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkVideo implements TypeInterface
{
    const NAME = "video";

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * VkFriend constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getName(): string
    {
        // TODO: Implement getName() method.
        return $this::NAME;
    }

    public function getData()
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
}