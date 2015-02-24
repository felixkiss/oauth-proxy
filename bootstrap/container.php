<?php

use Symfony\Component\HttpFoundation\Request;
use League\Container\Container;
use Keboola\Encryption\AesEncryptor;
use Felixkiss\Proxy\OAuth\PasswordGrantCredentials;
use Felixkiss\Proxy\ProxyHandler;
use Felixkiss\Proxy\Config\Cookie as CookieConfig;

$container = new Container;

$cookieConfig = new CookieConfig(getenv('COOKIE_NAME'), getenv('COOKIE_TTL'));
$container->singleton('Felixkiss\Proxy\Config\Cookie', $cookieConfig);

$credentials = new PasswordGrantCredentials(
    getenv('OAUTH_CLIENT_ID'),
    getenv('OAUTH_CLIENT_SECRET')
);
$container->singleton('Felixkiss\Proxy\OAuth\GrantCredentials', $credentials);

$encryptor = new AesEncryptor(getenv('COOKIE_ENCRYPTION_KEY'));
$container->singleton('Keboola\Encryption\EncryptorInterface', $encryptor);

$proxy = new ProxyHandler(getenv('BASE_URL'), new GuzzleHttp\Client);
$container->singleton('Felixkiss\Proxy\ProxyHandler', $proxy);

$request = Request::createFromGlobals();
$container->add('Symfony\Component\HttpFoundation\Request', $request);

return $container;
