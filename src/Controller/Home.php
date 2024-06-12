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

class Home extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $commitStripApi = new CommitStripApiService('http://www.commitstrip.com/en/feed/');
        $commitStripResponse = $commitStripApi->getResponse();
        $commitStripXmlConsumer = new CommitStripXmlService($commitStripResponse);
        $commitStripLinks = $commitStripXmlConsumer->getLinks();

        $newsApi = new NewsApiService("https://newsapi.org/v2/top-headlines?country=us&apiKey=c782db1cd730403f88a544b75dc2d7a0");
        $newsApiResponse = $newsApi->getResponse();
        $newsApiJsonConsumer = new NewsApiJsonService($newsApiResponse);
        $newsApiLinks = $newsApiJsonConsumer->getLinks();

        
        $images = [];
        $images = NewsApiService::scrawlToImages($newsApiLinks);
        $images = array_merge($images, CommitStripApiService::scrawlToImage($commitStripLinks));

        return $this->render('default/index.html.twig',[
            'images' => $images
            ]
        );
    }

}