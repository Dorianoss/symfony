<?php

namespace App\Service;

use GuzzleHttp\Client;

class TitleAPI
{
    /**
     * @var Client
     */
    private $client;

    /**
     * TitleAPI constructor.
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
    public function getTitle():array
    {
        return json_decode($this->client->request('GET', '/api/title')->getBody()->getContents(), true);
    }
}