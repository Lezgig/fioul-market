<?php

namespace App\Services\Consumer\APIConsumer\CommitStripApi;

use App\Services\Consumer\APIConsumer\APIConsumerAbstract;
use App\Services\Consumer\APIConsumer\CurlHttpClient;
use App\Services\Consumer\APIConsumer\APIConsumerInterface;
use Exception;

class CommitStripApiService extends APIConsumerAbstract implements APIConsumerInterface
{
    public function __construct()
    {

    }

    /**
     * Get the articles from the API
     * @return array the articles
     */
    public function scrawlToImages(array $urls): array
    {
        
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
                throw new Exception('Error while scrapping the images from the commit strip API');
            }
        }
        
        return $links;
    }
}