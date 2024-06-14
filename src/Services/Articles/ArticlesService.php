<?php 

namespace App\Services\Articles;

use App\Services\Consumer\APIConsumer\CommitStripApi\CommitStripApiService as CommitStripApiService;
use App\Services\Consumer\APIConsumer\CurlHttpClient;
use App\Services\Consumer\APIConsumer\NewsApi\NewsApiService as NewsApiService;
use App\Services\Consumer\APIResponseConsumer\XML\CommitStripXml;
use App\Services\Consumer\APIResponseConsumer\JSON\NewsApiJson;

class ArticlesService {

    private const NEWS_API_URL = "https://newsapi.org/v2/top-headlines?country=us&apiKey=c782db1cd730403f88a544b75dc2d7a0";
    private const COMMIT_STRIP_API_URL = "http://www.commitstrip.com/en/feed/";

    private $commitStripApi;
    private $newsApi;
    private $commitStripXmlConsumer;
    private $newsApiJsonConsumer;
    private $client;

    public function __construct(
        CommitStripApiService $commitStripApi,
        NewsApiService $newsApi,
        CommitStripXml $commitStripXmlConsumer,
        NewsApiJson $newsApiJsonConsumer,
        CurlHttpClient $client
        ) {
    
        $this->client = $client;
        $this->commitStripApi = $commitStripApi;
        $this->newsApi = $newsApi;
        $this->commitStripXmlConsumer = $commitStripXmlConsumer;
        $this->newsApiJsonConsumer = $newsApiJsonConsumer;
    }

    /**
     * Get articles from the two APIs
     * @return array
     */
    public function getArticles(): array
    {

        $articles = [];

        $catalog = [
            [
                'url' => self::NEWS_API_URL,
                'associated_api_response_consumer' => $this->newsApiJsonConsumer,
                'associated_api_consumer' => $this->newsApi
            ],
            [
                'url' => self::COMMIT_STRIP_API_URL,
                'associated_api_response_consumer' => $this->commitStripXmlConsumer,
                'associated_api_consumer' => $this->commitStripApi
            
            ]
        ];
        
        foreach ($catalog as $source) {
            $links = $this->sourceHandler(
                $source['url'],
                $source['associated_api_response_consumer'],
                $source['associated_api_consumer']
            );

            $articles = empty($articles) ? $links : array_merge($articles, $links);
            
        }

       
        return $articles;
    
    }

    /**
     * input array such as ['url' => 'https://www.example.com', 'associated_api_response_consumer' => '$newsApiJsonConsumer', 'associated_api_consumer' => '$newsApi']
     * @param array $source
     * @return array
     */
    private function sourceHandler (string $url, $formatConsumer, $apiConsumer): array
    {
        $response = $this->client->get($url);
        $links = $formatConsumer->extractLinks($response);
        $articles = $apiConsumer->scrawlToImages($links);     

        return $articles;

    } 
}