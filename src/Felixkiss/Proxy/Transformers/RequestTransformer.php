<?php namespace Felixkiss\Proxy\Transformers;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Post\PostBody;

class RequestTransformer
{
    /**
     * @param  Symfony\Component\HttpFoundation\Request $symfony
     * @param  GuzzleHttp\Message\Request $guzzle
     * @return GuzzleHttp\Message\Request
     */
    public function transform(SymfonyRequest $symfony, GuzzleRequest $guzzle)
    {
        $guzzle->setMethod($symfony->getMethod());
        $guzzle->setUrl($symfony->getUri());
        $guzzle->setPath($symfony->getPathInfo());
        $guzzle->setHeaders($symfony->headers->all());

        $postData = $symfony->request->all();
        if (sizeof($postData) > 0)
        {
            $body = new PostBody;
            foreach ($postData as $name => $value)
            {
                $body->setField($name, $value);
            }
            $guzzle->setBody($body);
        }

        return $guzzle;
    }
}
