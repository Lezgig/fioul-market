<?php

namespace App\Services\Consumer\APIResponseConsumer\JSON;

use App\Services\Consumer\AbstractConsumer;
use App\Services\Consumer\APIResponseConsumer\ApiResponseConsumerInterface;

class NewsApiJson extends AbstractConsumer implements ApiResponseConsumerInterface
{

    public function __construct()
    {
        
    }

    /**
     * Get the links from the API
     * @return array the links
     */
    public function extractLinks($json){

        $json = json_decode($json);
        
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