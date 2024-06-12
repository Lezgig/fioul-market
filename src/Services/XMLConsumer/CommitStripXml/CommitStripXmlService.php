<?php

namespace App\Services\XMLConsumer\CommitStripXml;

use SimpleXMLElement;
use App\Services\AbstractConsumer;

class CommitStripXmlService extends AbstractConsumer
{
    private string $file;

    public function __construct($file)
    {
        $this->file = $file;
        $this->consume($file);
    }
    
    private function consume($file)
    {
        try{
            return simplexml_load_string($file, 'SimpleXMLElement', LIBXML_NOCDATA);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getLinks(){
        $xml = self::consume($this->file);
        $sanitisedLinks = [];

        $articlesLinks = [];
        $articlesImages = [];

        foreach ($xml->channel->item as $item) {

            //if no url, skip
            if(!(string)$item->enclosure["url"]){
                continue;
            }

            //if not allowed extension, skip
            if(!parent::isItACorrectImageUrl((string)$item->enclosure["url"])){
                continue;
            }

            //if image already exists, skip
            if(in_array((string)$item->enclosure["url"], $articlesImages)){
                continue;
            }
            //if link already exists, skip
            if(in_array((string)$item->link, $articlesLinks)){
                continue;
            }
            
            $links[] = (string)$item->link;
        }
        return $links;
    }
}