<?php namespace Felixkiss\Proxy\OAuth;

class PasswordGrantCredentials extends GrantCredentials
{
    public function __construct($clientId, $clientSecret)
    {
        $this->credentials = [
            'grant_type'    => 'password',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
        ];
    }
}
