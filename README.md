# OpenStreetMap Provider for OAuth 2.0 Client

This package provides OpenStreetMap OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

```cmd
composer require jbelien/oauth2-OpenStreetMap
```

## Usage

```php
$OpenStreetMapProvider = new \JBelien\OAuth2\Client\Provider\OpenStreetMap([
    'clientId'     => 'yourId',          // The client ID assigned to you by OpenStreetMap.org
    'clientSecret' => 'yourSecret',      // The client password assigned to you by OpenStreetMap.org
    'redirectUri'  => 'yourRedirectUri', // The return URL you specified for your app on OpenStreetMap.org
    'dev'          => false              // Whether to use the OpenStreetMap test environment at https://master.apis.dev.openstreetmap.org/
]);

// Get authorization code
if (!isset($_GET['code'])) {
    // Options are optional, defaults to 'read_prefs' only
    $options = ['scope' => 'read_prefs read_gpx'];
    // Get authorization URL
    $authorizationUrl = $OpenStreetMapProvider->getAuthorizationUrl($options);

    // Get state and store it to the session
    $_SESSION['oauth2state'] = $OpenStreetMapProvider->getState();

    // Redirect user to authorization URL
    header('Location: ' . $authorizationUrl);
    exit;
// Check for errors
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    exit('Invalid state');
} else {
    // Get access token
    try {
        $accessToken = $OpenStreetMapProvider->getAccessToken(
            'authorization_code',
            [
                'code' => $_GET['code']
            ]
        );
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }

    // Get resource owner
    try {
        $resourceOwner = $OpenStreetMapProvider->getResourceOwner($accessToken);
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }
        
    // Now you can store the results to session etc.
    $_SESSION['accessToken'] = $accessToken;
    $_SESSION['resourceOwner'] = $resourceOwner;
    
    var_dump(
        $resourceOwner->getId(),
        $resourceOwner->getDisplayName(),
        $resourceOwner->getAccountCreated(),
        $resourceOwner->getImage(),
        $resourceOwner->getChangesetsCount(),
        $resourceOwner->getLanguages(),
        $resourceOwner->toArray()
    );
}
```

For more information see the PHP League's general usage examples.

## Testing

``` bash
./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](https://github.com/jbelien/oauth2-openstreetmap/blob/master/LICENSE) for more information.
