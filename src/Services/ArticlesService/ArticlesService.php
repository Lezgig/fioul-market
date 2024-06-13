<?php 

namespace App\Services\ArticlesService;

use App\Services\Consumer\APIConsumer\CommitStripApi\CommitStripApiService;
use App\Services\Consumer\APIConsumer\NewsApi\NewsApiService;
use App\Services\Consumer\JSONConsumer\NewsAPIJson\NewsApiJsonService;
use App\Services\Consumer\XMLConsumer\CommitStripXml\CommitStripXmlService;



class ArticlesService {
    
    public static function getArticles(){

        $articles = [];
        $commitStripApi = new CommitStripApiService('http://www.commitstrip.com/en/feed/');
        $commitStripResponse = $commitStripApi->getResponse();
        $commitStripXmlConsumer = new CommitStripXmlService($commitStripResponse);
        $commitStripLinks = $commitStripXmlConsumer->getLinks();

        $newsApi = new NewsApiService("https://newsapi.org/v2/top-headlines?country=us&apiKey=c782db1cd730403f88a544b75dc2d7a0");
        $newsApiResponse = $newsApi->getResponse();
        $newsApiJsonConsumer = new NewsApiJsonService($newsApiResponse);
        $newsApiLinks = $newsApiJsonConsumer->getLinks();


        $articles = NewsApiService::scrawlToImages($newsApiLinks);
        $articles = array_merge($articles, CommitStripApiService::scrawlToImage($commitStripLinks));
        return $articles;
    
    }
}