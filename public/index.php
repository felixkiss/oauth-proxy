<?php

require '../vendor/autoload.php';

Dotenv::load('..');

$container = require '../bootstrap/container.php';

$router = new League\Route\RouteCollection($container);

$router->addRoute('POST', '/auth', 'Felixkiss\Proxy\OAuthController::handle');
$router->addRoute('DELETE', '/auth', 'Felixkiss\Proxy\OAuthController::delete');
$router->addRoute(
    'GET', '/api/resource/{id}', 'Felixkiss\Proxy\ApiController::resource'
);
$router->addRoute('POST', '/api/auth', 'Felixkiss\Proxy\ApiController::auth');

$dispatcher = $router->getDispatcher();
$request = $container->get('Symfony\Component\HttpFoundation\Request');

try
{
    $response = $dispatcher->dispatch(
        $request->getMethod(),
        $request->getPathInfo()
    );

    $response->send();
}
catch (League\Route\Http\Exception\NotFoundException $exception)
{
    // Handle everything else
    $response = $container->get('Symfony\Component\HttpFoundation\Response');
    $proxy = $container->get('Felixkiss\Proxy\ProxyController');

    $proxy->handle($request, $response)->send();
}
catch(League\Route\Http\Exception $exception)
{
    $response = $exception->getJsonResponse();
    $response->send();
}
catch(Exception $exception)
{
    $response = $container->get('Symfony\Component\HttpFoundation\Response');
    $response->setStatusCode(500);
    $response->send();
}
