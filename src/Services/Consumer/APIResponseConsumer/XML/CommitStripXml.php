<?php

namespace App\Services\Consumer\APIResponseConsumer\XML;

use SimpleXMLElement;
use App\Services\Consumer\AbstractConsumer;
use App\Services\Consumer\APIResponseConsumer\ApiResponseConsumerInterface;

class CommitStripXml extends AbstractConsumer implements ApiResponseConsumerInterface
{

    public function __construct()
    {

    }

    /**
     * Get the links from the API
     * @return array the links
     */
    public function extractLinks($xml): array
    {

        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

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
            $links[] = array((string)$item->link, (string)$item->title);
        }
        return $links;
    }
}