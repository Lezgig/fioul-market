<?php

namespace App\Services\Consumer;

abstract class AbstractConsumer
{

    protected const ALLOWED_FILES = ['jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF'];

    /**
     * Sanitise the URL
     * @param string $url
     * @return string the sanitised URL
     */
    protected static function sanitiseUrl($url) {
        return preg_replace('/\?.*/', '', $url);
    }

    /**
     * Check if the URL is a correct image URL
     * @param string $imageUrl
     * @return bool true if it is a correct image URL
     */
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