<?php

namespace App\Services\APIConsumer\CommitStripApi;

use App\Services\APIConsumer\APIConsumerAbstract;
use App\Services\APIConsumer\CurlHttpClient;

class CommitStripApiService extends APIConsumerAbstract
{
    private const BASE_URL = "http://www.commitstrip.com/en/feed/";

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
        simplexml_load_string($this->response, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
}