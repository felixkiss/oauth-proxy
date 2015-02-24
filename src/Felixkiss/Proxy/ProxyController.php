<?php namespace Felixkiss\Proxy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

use Keboola\Encryption\EncryptorInterface as Encryptor;
use Felixkiss\Proxy\Config\Cookie as CookieConfig;

class ProxyController
{
    /**
     * @var Felixkiss\Proxy\ProxyHandler
     */
    private $handler;

    /**
     * @var Keboola\Encryption\EncryptorInterface
     */
    private $encryptor;

    /**
     * @var Felixkiss\Proxy\Config\Cookie
     */
    private $cookieConfig;

    /**
     * @param GuzzleHttp\Client $client
     * @param Keboola\Encryption\EncryptorInterface $encryptor
     * @param Felixkiss\Proxy\Config\Cookie $cookieConfig
     */
    public function __construct(
        ProxyHandler $handler,
        Encryptor $encryptor,
        CookieConfig $cookieConfig)
    {
        $this->handler = $handler;
        $this->encryptor = $encryptor;
        $this->cookieConfig = $cookieConfig;
    }

    /**
     * @param  Symfony\Component\HttpFoundation\Request  $request
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Response $response)
    {
        $cookies = $request->cookies->all();
        if (isset($cookies[$this->cookieConfig->getName()]))
        {
            // Decrypt cookie and include Authorization header
            $encrypted = $cookies[$this->cookieConfig->getName()];
            $tokens = json_decode($this->encryptor->decrypt($encrypted), true);
            $request->headers->set(
                'Authorization',
                "{$tokens['token_type']} {$tokens['access_token']}"
            );
        }

        return $this->handler->handle($request, $response);
    }
}
