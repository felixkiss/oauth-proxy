<?php namespace Felixkiss\Proxy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Felixkiss\Proxy\OAuth\GrantCredentials;
use Keboola\Encryption\EncryptorInterface as Encryptor;
use Felixkiss\Proxy\Config\Cookie as CookieConfig;

class OAuthController
{
    /**
     * @var Felixkiss\Proxy\OAuth\GrantCredentials
     */
    private $grant;

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
     * @param Felixkiss\Proxy\OAuth\GrantCredentials $grant
     * @param Felixkiss\Proxy\ProxyHandler $handler
     * @param Keboola\Encryption\EncryptorInterface $encryptor
     * @param Felixkiss\Proxy\Config\Cookie $cookieConfig
     */
    public function __construct(
        GrantCredentials $grant,
        ProxyHandler $handler,
        Encryptor $encryptor,
        CookieConfig $cookieConfig)
    {
        $this->grant = $grant;
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
        // We are about to change the body of the request
        // => Remove out-of-date content-type and content-length
        $request->headers->remove('content-type');
        $request->headers->remove('content-length');

        // Add OAuth grant information to request
        $request->request->add($this->grant->getCredentials());

        // Proxy request to actual server
        $response = $this->handler->handle($request, $response);

        // Encrypt tokens and place into a cookie
        $tokens = $response->getContent();
        $encrypted = $this->encryptor->encrypt($tokens);

        $cookie = new Cookie(
            $this->cookieConfig->getName(),
            $encrypted,
            time() + $this->cookieConfig->getTtl()
        );
        $response->headers->setCookie($cookie);
        $response->setContent('{}');

        return $response;
    }
}
