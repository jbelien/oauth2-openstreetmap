<?php

namespace JBelien\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

class OpenStreetMap extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected $dev = false;
    protected $base_url = 'https://www.openstreetmap.org' ;
    protected $base_url_dev = 'https://master.apis.dev.openstreetmap.org' ;

    /**
     * Available options for OpenStreetMap provider:
     * - dev : if True use "base_url_dev" otherwise "base_url".
     * - osm_base_url : Url to use when "dev" is False.
     * - osm_base_url_dev : Url to use when "dev" is True.
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);

        if (isset($options['dev'])) {
            $this->dev = (bool) $options['dev'];
        }
        if (isset($options['osm_base_url'])) {
            $this->base_url = $options['osm_base_url'];
        }
        if (isset($options['osm_base_url_dev'])) {
            $this->base_url_dev = $options['osm_base_url_dev'];
        }
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->dev ?
            $this->base_url_dev.'/oauth2/authorize' :
            $this->base_url.'/oauth2/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->dev ?
            $this->base_url_dev.'/oauth2/token' :
            $this->base_url.'/oauth2/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->dev ?
            $this->base_url_dev.'/api/0.6/user/details.json' :
            $this->base_url.'/api/0.6/user/details.json';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return ['read_prefs'];
    }

    /**
     * Check a provider response for errors.
     *
     * @param  ResponseInterface $response
     * @param  array|string $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            $statusCode = $response->getStatusCode();

            $error = $data['error'];
            $errorDescription = $data['error_description'];
            $errorLink = (isset($data['error_uri']) ? $data['error_uri'] : false);

            throw new IdentityProviderException(
                $statusCode . ' - ' . $errorDescription . ': ' . $error . ($errorLink ? ' (see: ' . $errorLink . ')' : ''),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     *
     * @return League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new OpenStreetMapResourceOwner($response);
    }

    /**
     * Returns a prepared request for requesting an access token.
     *
     * @param array $params
     *
     * @return Psr\Http\Message\RequestInterface
     */
    protected function getAccessTokenRequest(array $params)
    {
        $request = parent::getAccessTokenRequest($params);

        $uri = $request->getUri()
            ->withUserInfo($this->clientId, $this->clientSecret);

        return $request->withUri($uri);
    }
}
