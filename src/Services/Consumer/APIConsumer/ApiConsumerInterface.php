<?php 

namespace App\Services\Consumer\APIConsumer;

interface ApiConsumerInterface
{
    /**
     * Get the articles from the API
     * @return array the articles
     */
    public function scrawlToImages(array $url): array;
}