<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\APIConsumer\NewsApi\NewsApiService;
use App\Services\APIConsumer\CommitStripApi\CommitStripApiService;
use App\Services\XMLConsumer\CommitStripXml\CommitStripXmlService;
use App\Services\JSONConsumer\NewsAPIJson\NewsApiJsonService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Contracts\Cache\ItemInterface;
class Home extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function homepage(Request $request)
    {

        $cache = new FilesystemAdapter();
        
        $cachedArticles = $cache->get('articles', function (ItemInterface $item){
            $item->expiresAfter(3600);
            return $this->getArticles();
        });
        
        return $this->render('default/index.html.twig',[
            'articles' => $cachedArticles
            ]
        );
    
    }

    private function getArticles(){
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