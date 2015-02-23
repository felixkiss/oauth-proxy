<?php namespace Felixkiss\Proxy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatchAll
{
    public function handle(Request $request, Response $response)
    {
        $response->setContent('catch all');
        return $response;
    }
}
