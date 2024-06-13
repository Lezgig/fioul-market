<?php

namespace App\Tests;

use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use App\Services\APIConsumer\CommitStripApi\CommitStripApiService;
use App\Services\APIConsumer\NewsApi\NewsApiService;
use App\Services\JSONConsumer\NewsAPIJson\NewsApiJsonService;
use App\Services\APIConsumer\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\MockHttpClient;
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

    

    

}