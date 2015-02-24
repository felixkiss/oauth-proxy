# oauth-proxy

This is a standalone web server written in PHP that acts as a proxy between
your OAuth secured API and your client-side application.

It is basically an implementation of the approach to OAuth + client-side web
apps as discussed by an [article by Alex Bilbie](http://alexbilbie.com/2014/11/oauth-and-javascript/).

Right now, it only supports the password authorisation grant.

## Deployment

1. Clone the repository and point your webserver to `public/`.
2. Run `composer install --no-dev`
3. Create and edit the config file (`.env` based on `.env.example`).

## Theory

For an in-depth explanation, you should really check out the [article by Alex Bilbie](http://alexbilbie.com/2014/11/oauth-and-javascript/). The following is the bottom line described by all requests that are made.

Request to the proxy:

```
POST /auth HTTP/1.1
Host: proxy.example.com

username=foo
&password=bar
```

Request to the actual API server:

```
POST /auth HTTP/1.1
Host: api.example.com

grant_type=password
&client_id=proxy-client
&client_secret=52d14e22b9101
&username=foo
&password=bar
```

Response from the actual API server:

```json
{
    "access_token": "DDSHs55zpG51Mtxnt6H8vwn5fVJ230dF",
    "refresh_token": "24QmIt2aV1ubaenB2D6G0se5pFRk4W05",
    "token_type": "Bearer",
    "expires": 1415741799
}
```

Proxy encrypts the tokens in a cookie and return a success message:

```
HTTP/1.1 200 OK
```

Client sends request for a resource to the proxy:

```
GET /resource/123 HTTP/1.1
Cookie: <encrypted cookie with tokens>
Host: example.com
```

Proxy decrypts the cookie and sends request to the actual API server:

```
GET /resource/123 HTTP/1.1
Host: api.example.com
Authorization: Bearer DDSHs55zpG51Mtxnt6H8vwn5fVJ230dF
```

Proxy returns response to the client:

```json
{
    "resource": {
        "id": 123,
        "foo": "bar"
    }
}
```

## License

MIT (See LICENSE.md)
