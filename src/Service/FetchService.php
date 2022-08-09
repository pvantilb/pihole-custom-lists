<?php


namespace PvListManager\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FetchService
{

    /**
     * Undocumented variable
     *
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getList($url, $asResponse = false): string|ResponseInterface
    {
        $resp = $this->client->request('GET', 'https://v.firebog.net/hosts/static/w3kbl.txt');

        if(!$asResponse) {
            return $resp->getContent();
        } else {
            return $resp;
        }

    }

}