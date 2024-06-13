<?php

namespace App\Services\Consumer\APIConsumer;

interface HttpClientInterface
{
    /**
     * @param string $url l'url de la requête
     * @return string le contenu de la réponse
     * @throws \Exception si la requête échoue
     */
    public function get(string $url): string;

    /**
     * @param string $url l'url de la requête
     * @param array $data les données à envoyer
     * @return string le contenu de la réponse
     * @throws \Exception si la requête échoue
     */
    public function post(string $url, array $data): string;

    /**
     * @param string $url l'url de la requête
     * @param array $data les données à envoyer
     * @return string le contenu de la réponse
     * @throws \Exception si la requête échoue
     */
    public function put(string $url, array $data): string;

    /**
     * @param string $url l'url de la requête
     * @return string le contenu de la réponse
     * @throws \Exception si la requête échoue
     */
    public function delete(string $url): string;
}