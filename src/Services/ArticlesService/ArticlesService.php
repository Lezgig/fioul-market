<?php 

namespace App\Services\ArticlesService;

use App\Services\Consumer\APIConsumer\CommitStripApi\CommitStripApiService;
use App\Services\Consumer\APIConsumer\NewsApi\NewsApiService;
use App\Services\Consumer\JSONConsumer\NewsAPIJson\NewsApiJsonService;
use App\Services\Consumer\XMLConsumer\CommitStripXml\CommitStripXmlService;



class ArticlesService {

    private const NEWS_API_URL = "https://newsapi.org/v2/top-headlines?country=us&apiKey=c782db1cd730403f88a544b75dc2d7a0";
    private const COMMIT_STRIP_API_URL = "http://www.commitstrip.com/en/feed/";

    /**
     * Get articles from the two APIs
     * @return array
     */
    public static function getArticles(){

        $articles = [];

        // Get articles from the API
        $commitStripApi = new CommitStripApiService(self::COMMIT_STRIP_API_URL);
        $commitStripResponse = $commitStripApi->getResponse();
        // Get links from the API
        $commitStripXmlConsumer = new CommitStripXmlService($commitStripResponse);
        $commitStripLinks = $commitStripXmlConsumer->getLinks();

        // Get articles from the API
        $newsApi = new NewsApiService(self::NEWS_API_URL);
        $newsApiResponse = $newsApi->getResponse();

        // Get links from the API
        $newsApiJsonConsumer = new NewsApiJsonService($newsApiResponse);
        $newsApiLinks = $newsApiJsonConsumer->getLinks();

        // Pull images from the urls
        $articles = NewsApiService::scrawlToImages($newsApiLinks);
        $articles = array_merge($articles, CommitStripApiService::scrawlToImage($commitStripLinks));
        
        return $articles;
    
    }
}