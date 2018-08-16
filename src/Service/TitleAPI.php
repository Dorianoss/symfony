<?php

namespace App\Service;

use GuzzleHttp\Client;

class TitleAPI
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getTitle()
    {
        return $this->client->request('GET', '/api/title');
    }
}