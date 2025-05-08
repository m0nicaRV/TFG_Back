<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Calendar;

class CalendarController extends Controller
{
    public function token(){

        $client = new Client();
        $file=config_path('client_secret_65185540485-7hhkmbiso5t6rt2f54r4u062tesu9ej3.apps.googleusercontent.com.json');

        $client->setAuthConfig( $file);
        $client->addScope([\Google\Service\Calendar::CALENDAR_READONLY]);

        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
        $client->setAccessType('offline');
        $sample_passthrough_value ='65185540485-7hhkmbiso5t6rt2f54r4u062tesu9ej3.apps.googleusercontent.com';
        $client->setState($sample_passthrough_value);
        $client->setLoginHint('daw10.2024@gmail.com');

        $client->setPrompt('consent');
        $client->setIncludeGrantedScopes(true);
        return $auth_url= $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        return $client->fetchAccessTokenWithAuthCode($_GET['code']);

    }
}
