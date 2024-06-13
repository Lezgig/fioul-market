<?php

namespace App\Services\Consumer;

abstract class AbstractConsumer
{

    protected const ALLOWED_FILES = ['jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF'];

    protected static function sanitiseUrl($url) {
        return preg_replace('/\?.*/', '', $url);
    }

    protected static function isItACorrectImageUrl($imageUrl) :bool
    {

        if(preg_match('/www\.|http?s\:\/\//', $imageUrl) == 0){
            return false;
        }

        $sanitisedString = self::sanitiseUrl($imageUrl);
        $urlExploded = explode('.', $sanitisedString);
        $extension = end($urlExploded);
        if (!in_array($extension, self::ALLOWED_FILES)) {
            return false;
        }
        return true;
    }

    

}