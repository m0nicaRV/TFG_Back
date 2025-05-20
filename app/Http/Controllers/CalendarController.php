<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Storage;

class CalendarController extends Controller
{

    const TOKEN_FILE_PATH='google_tokens/system_user_tokens.json';

    private function loadTokens(): array
    {
        if (Storage::disk('local')->exists(self::TOKEN_FILE_PATH)) {
            return json_decode(Storage::disk('local')->get(self::TOKEN_FILE_PATH), true);
        }
        return [];
    }

    private function saveTokens(array $tokens): void
    {
        Storage::disk('local')->put(self::TOKEN_FILE_PATH, json_encode($tokens));
    }

    private function getGoogleClient(array $tokens = []): Client
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        if (!empty($tokens)) {
            $client->setAccessToken($tokens);
        }

        return $client;
    }

    public function redirectToGoogle()
    {
        $client = $this->getGoogleClient();
        $client->addScope(Gmail::GMAIL_SEND);
        $client->addScope(Calendar::CALENDAR_EVENTS);

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = $this->getGoogleClient();

        if ($request->missing('code')) {
            $error = $request->input('error', 'access_denied');
            return view('auth.google-callback-script', ['status' => 'error', 'message' => $error]);
        }

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);

            $this->saveTokens($accessToken);

            return view('auth.google-callback-script', ['status' => 'success', 'message' => 'Autenticación exitosa']);

        } catch (Exception $e) {
            \Log::error('Error en el callback de Google: ' . $e->getMessage());
            return view('auth.google-callback-script', ['status' => 'error', 'message' => 'Error en autenticación: ' . $e->getMessage()]);
        }
    }



}
