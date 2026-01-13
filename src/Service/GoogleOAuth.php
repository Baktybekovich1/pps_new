<?php


namespace App\Service;

use League\OAuth2\Client\Provider\Google;

class GoogleOAuth extends Google
{
    public function __construct()
    {
        parent::__construct([
            'clientId' => $_ENV['GOOGLE_CLIENT_ID'],
            'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'redirectUri' => $_ENV['GOOGLE_REDIRECT_URI'],
//            'hostedDomain' => '*',
        ]);
    }
}