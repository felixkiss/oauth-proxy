<?php

require '../vendor/autoload.php';

use League\Container\Container;
use League\Route\Http\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$container = new Container;
$router = new League\Route\RouteCollection($container);

$router->addRoute('POST', '/auth', 'Felixkiss\Proxy\OAuth::handle');

$dispatcher = $router->getDispatcher();
$request = Request::createFromGlobals();

try
{
    $response = $dispatcher->dispatch(
        $request->getMethod(),
        $request->getPathInfo()
    );

    $response->send();
}
catch(NotFoundException $exception)
{
    // Handle everything else
    $catchAll = $container->get('Felixkiss\Proxy\CatchAll');
    $response = $container->get('Symfony\Component\HttpFoundation\Response');

    $response = $catchAll->handle($request, $response);
    $response->send();
}
