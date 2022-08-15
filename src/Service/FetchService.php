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

    public function getList($url, $rawContent = false): string|array
    {
        $url = 'https://v.firebog.net/hosts/static/w3kbl.txt';
        $resp = $this->client->request('GET', $url);

        if($rawContent) {
            return $resp->getContent();
        } else {
            return explode(PHP_EOL,$resp->getContent());
        }

    }

}