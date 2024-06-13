<?php

namespace App\Services\Consumer\APIConsumer\CommitStripApi;

use App\Services\Consumer\APIConsumer\APIConsumerAbstract;
use App\Services\Consumer\APIConsumer\CurlHttpClient;

class CommitStripApiService extends APIConsumerAbstract
{
    private const BASE_URL = "http://www.commitstrip.com/en/feed/";

    public function __construct($url = self::BASE_URL)
    {
        $this->url = $url;
        $this->consume();
    }

    /**
     * Get the articles from the API
     * @return array the articles
     */
    public static function scrawlToImage(array $urls){
        
        $links = [];
        $i=0;
        foreach($urls as $url){
            try{
                $doc = new \DomDocument();
                @$doc->loadHTMLFile($url[0]);
                $xpath = new \DomXpath($doc);
                $image = $xpath->query('//img[contains(@class,"size-full")]/@src');
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

    /**
     * Get the articles from the API
     * @return array the articles
     */
    private function consume()
    {
        $client = new CurlHttpClient();
        $this->response = $client->get($this->url);
    }
}