<?php

namespace App\Service;

use GuzzleHttp\Client;

class VkAPI
{
    /**
     * @var Client
     */
    private $client;

    /**
     * VkAPI constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFriends():array
    {
        return json_decode($this->client->request('GET', '/method/friends.get')->getBody()->getContents(), true);return json_decode($this->request('GET', '/method/friends.get')->getBody()->getContents(), true);
    }
}