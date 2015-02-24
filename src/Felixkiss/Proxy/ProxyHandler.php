<?php namespace Felixkiss\Proxy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use Felixkiss\Proxy\Transformers\RequestTransformer;
use Felixkiss\Proxy\Transformers\ResponseTransformer;

class ProxyHandler
{
    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param GuzzleHttp\Client $client
     */
    public function __construct($baseUrl, Client $client)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $client;
    }

    /**
     * @param  Symfony\Component\HttpFoundation\Request  $request
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Response $response)
    {
        try
        {
            $proxyRequest = $this->client->createRequest('GET');
            $proxyRequest = (new RequestTransformer)->transform(
                $request,
                $proxyRequest
            );

            // Redirect request to configured base url
            $proxyRequest->setUrl(
                $this->baseUrl . $proxyRequest->getResource()
            );
            $proxyResponse = $this->client->send($proxyRequest);

            $response = (new ResponseTransformer)->transform(
                $proxyResponse,
                $response
            );

            return $response;
        }
        catch(ClientException $exception)
        {
            return (new ResponseTransformer)->transform(
                $exception->getResponse(),
                $response
            );
        }
    }
}
