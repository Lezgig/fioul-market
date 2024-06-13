<?php

namespace App\Services\Consumer\JSONConsumer\NewsAPIJson;

use App\Services\Consumer\AbstractConsumer;

class NewsApiJsonService extends AbstractConsumer
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }
    
    private function consume($response)
    {
        try {
            return json_decode($response);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getLinks(){

        $json = self::consume($this->response);
        
        $links = [];
        $articlesLinks = [];
        $articlesImages = [];

        foreach ($json->articles as $item) {

            //if no url, skip
            if(!(string)$item->url){
                continue;
            }
            //if no urlToImage, skip
            if(!(string)$item->urlToImage){
                continue;
            }
            //if not allowed extension, skip
            if(!parent::isItACorrectImageUrl((string)$item->urlToImage)){
                continue;
            }

            //if image already exists, skip
            if(in_array((string)$item->urlToImage, $articlesImages)){
                continue;
            }

            //if link already exists, skip
            if(in_array((string)$item->url, $articlesLinks)){
                continue;
            }
            $links[] = array((string)$item->url, (string)$item->title);
        }
        
        return $links;
    }
}