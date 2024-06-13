<?php

namespace App\Services\Consumer\APIConsumer\NewsApi;

use App\Services\Consumer\APIConsumer\APIConsumerAbstract;
use App\Services\Consumer\APIConsumer\APIConsumerInterface;
use App\Services\Consumer\APIConsumer\CurlHttpClient;
use App\Services\Consumer\JSONConsumer\NewsAPIJson\NewsApiJsonService;

class NewsApiService extends APIConsumerAbstract{

    private const BASE_URL = "https://newsapi.org/v2";

    public function __construct($url = self::BASE_URL)
    {   
        $this->url = $url;
        $this->consume();
    }

    public static function scrawlToImages(array $urls){

        $links = [];
        $i=0;
        foreach($urls as $url){
            try{
                $doc = new \DomDocument();
                @$doc->loadHTMLFile($url[0]);
                $xpath = new \DomXpath($doc);
                $image = $xpath->query('//img/@src');
                if($image->length == 0){
                    continue;
                }
                $src=$image[0]->value;
                
                if(parent::isItACorrectImageUrl($src) == false){
                    unset($urls[$i]);
                    continue;
                }
                
                array_push($links,array($url[0], parent::sanitiseUrl($src), $url[1]));
                $i++;
            }catch(Exception $e){
                
            }
        }   
        return $links;   
    }

    private function consume()
    {
        $client = new CurlHttpClient();
        $this->response = $client->get($this->url);
    }
}