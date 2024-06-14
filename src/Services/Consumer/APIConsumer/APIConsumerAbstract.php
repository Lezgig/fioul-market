<?php

namespace App\Services\Consumer\APIConsumer;

use App\Services\Consumer\AbstractConsumer;

abstract class APIConsumerAbstract extends AbstractConsumer{

    public function __construct() {

    }

    /**
     * Get the articles from the API
     * @return array the articles
     */
    public function consume($url): string
    {
        $client = new CurlHttpClient();
        return $client->get($url);
    }

}