<?php

namespace App\Services\APIConsumer\NewsApi;

use App\Services\APIConsumer\APIConsumerAbstract;
use App\Services\APIConsumer\APIConsumerInterface;
use App\Services\APIConsumer\CurlHttpClient;

class NewsApiService extends APIConsumerAbstract{

    private const BASE_URL = "https://newsapi.org/v2";

    public function __construct($url = self::BASE_URL)
    {   
        $this->url = $url;
        $this->consume();
        $this->response;
    }

    private function setResponse($response)
    {
        $this->response = $response;
    }

    private function consume()
    {
        $client = new CurlHttpClient();
        $this->setResponse($client->get($this->url));
    }
}