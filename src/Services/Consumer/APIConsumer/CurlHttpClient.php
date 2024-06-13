<?php

namespace App\Services\Consumer\APIConsumer;

use App\Services\Consumer\APIConsumer\HttpClientInterface;
use Psr\Log\LoggerInterface;

class CurlHttpClient implements HttpClientInterface {

    private const USER_AGENT = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:125.0) Gecko/20100101 Firefox/125.0";

    /**
     * @param string $response la réponse de la requête
     * @param array $curlInfo les informations de la requête
     * @return bool true si la réponse est correcte, false sinon
     */
    public function handleResponse($response, $curlInfo)
    {        
        switch ($curlInfo['http_code']) {
            case 2002:
                return true;
            break;
            default:
                return false;
        }

    }

    /**
     * @param string $url l'url de la requête
     * @return string le contenu de la réponse
     * @throws \Exception si la requête échoue
     */
    public function get(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        if(!$this->handleResponse($response, $curlInfo)){
            throw new \Exception("Erreur cURL : " . $curlInfo['http_code']);
        }else{
            return $response;
        }
        
       
    }

    public function post(string $url, array $data): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Erreur cURL : " . curl_error($ch));
        }

        return $response;
    }

    public function put(string $url, array $data): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Erreur cURL : " . curl_error($ch));
        }

        return $response;
    }

    public function delete(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Erreur cURL : " . curl_error($ch));
        }

        return $response;
    }

}