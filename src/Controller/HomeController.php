<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use App\Services\ArticlesService\ArticlesService;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const CACHE_KEY = 'articles';

    /**
     * Homepage
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {
        $cache = new FilesystemAdapter();

        // Get articles from cache or from the service if data are not found in cache
        $cachedArticles = $cache->get(self::CACHE_KEY, function (ItemInterface $item){
            $item->expiresAfter(3600);
            // Get articles from the service
            return ArticlesService::getArticles();
        });
        
        return $this->render('default/index.html.twig',[
            'articles' => $cachedArticles
            ]
        );
    
    }
}