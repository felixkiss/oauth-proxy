<?php namespace Felixkiss\Proxy\Transformers;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use GuzzleHttp\Message\Response as GuzzleResponse;

class ResponseTransformer
{
    /**
     * @param  GuzzleHttp\Message\Response $guzzle
     * @param  Symfony\Component\HttpFoundation\Response $symfony
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function transform(GuzzleResponse $guzzle, SymfonyResponse $symfony)
    {
        $symfony->setStatusCode($guzzle->getStatusCode());
        $symfony->setContent($guzzle->getBody());
        $symfony->headers = new ResponseHeaderBag($guzzle->getHeaders());

        return $symfony;
    }
}
