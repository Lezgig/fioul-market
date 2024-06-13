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
use App\Services\ArticlesService\ArticlesService;

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
            return ArticlesService::getArticles();
        });
        
        return $this->render('default/index.html.twig',[
            'articles' => $cachedArticles
            ]
        );
    
    }
}