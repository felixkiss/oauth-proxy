<?php namespace Felixkiss\Proxy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuth
{
    public function handle(Request $request, Response $response)
    {
        $response->setContent('oauth');
        return $response;
    }
}
