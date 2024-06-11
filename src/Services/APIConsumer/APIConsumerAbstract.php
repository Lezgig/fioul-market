<?php

namespace App\Services\APIConsumer;

abstract class APIConsumerAbstract {

    protected $url;
    protected $response;
    protected $jsonResponse;

    public function __construct(string $url) {
        $this->url = $url;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getJsonResponse() {
        try {
            return json_decode($this->response);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}