<?php

namespace App\Services\Consumer\APIResponseConsumer;

interface ApiResponseConsumerInterface
{
    /**
     * Get the links from the API
     * @return array the links
     */
    public function extractLinks($response);
} 