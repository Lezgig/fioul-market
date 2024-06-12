<?php

namespace App\Services\APIConsumer;

use App\Services\AbstractConsumer;

abstract class APIConsumerAbstract extends AbstractConsumer{

    protected string $url;
    protected string $response;
    protected $jsonResponse;

    public function __construct(string $url) {
        $this->url = $url;
    }

    public function getResponse() {
        return $this->response;
    }

}