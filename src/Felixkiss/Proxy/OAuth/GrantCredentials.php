<?php namespace Felixkiss\Proxy\OAuth;

abstract class GrantCredentials
{
    protected $credentials;

    public function getCredentials()
    {
        return $this->credentials;
    }
}
