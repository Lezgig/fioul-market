<?php

namespace App\Tests;


use App\Services\Consumer\NewsAPIJson\NewsApiJsonService;
use App\Services\ArticlesService\ArticlesService;
use PHPUnit\Framework\TestCase;
use App\Services\Consumer\APIConsumer\CurlHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ConsumerTest extends TestCase
{
    /**
     * @Given I have a NewsApiService
     * @When I call the NewsApiJsonService and I get a 500 code response
     * @Then I should get an error
     */
    public function testNewsApiServiceWith500Code()
    {
        $response = new MockResponse('Resource not available', ['http_code' => 500]);

        $curlHttpClient = new CurlHttpClient();
        $curlInfo = array('http_code' => 500);
        $this->assertEquals($curlHttpClient->handleResponse($response, $curlInfo ), false);
    }

    /**
     * @Given I have a NewsApiService
     * @When I call the NewsApiJsonService and I get a 200 code response
     * @Then I should get the response
     */
    public function testNewsApiServiceWith200Code()
    {
        $response = new MockResponse('{"status":"ok"}', ['http_code' => 200]);

        $curlHttpClient = new CurlHttpClient();
        $curlInfo = array('http_code' => 200);
        $this->assertEquals($curlHttpClient->handleResponse($response, $curlInfo ), true);
    }

    /**
     * Given I have an ArticlesService
     * When I call the getArticles method
     * Then I should get an array of articles
     */
    public function testGetArticles()
    {
        $articles = ArticlesService::getArticles();
        $this->assertIsArray($articles);
    }
    

}