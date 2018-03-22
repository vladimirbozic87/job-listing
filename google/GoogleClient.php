<?php

namespace GoogleClientApi;

use Google_Service_Drive;
use Google_Client;
use Google_Service_Drive_DriveFile;

class GoogleClient
{
    const APPLICATION_NAME   = 'Drive API PHP Quickstart';
    const SCOPES             = [Google_Service_Drive::DRIVE_FILE];

    protected $client_secret;
    protected $credentials;
    protected $client;

    public function __construct(string $client_secret, string $credentials)
    {
        $this->client_secret = $client_secret;
        $this->credentials   = $credentials;
        $this->client = new Google_Client();
        $this->client->setApplicationName(GoogleClient::APPLICATION_NAME);
        $this->client->setScopes(GoogleClient::SCOPES);
        $this->client->setAuthConfig($this->client_secret);
        $this->client->setAccessType('offline');
    }

    public function expandHomeDirectory($path)
    {
        return __DIR__ . '/credentials/' . $path;
    }

    public function getTokenFromWeb()
    {
        $authUrl = $this->client->createAuthUrl();

        echo'<script>window.open("' . $authUrl . '", "_blank");</script>';

        return $authUrl;
    }

    public function setTokenFromWeb(string $authCode)
    {
        $credentialsPath = $this->expandHomeDirectory($this->credentials);

        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if(!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));

        $this->client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($this->client->getAccessToken()));
        }
        return $this->client;
    }

    public function setToken()
    {
        $credentialsPath = $this->expandHomeDirectory($this->credentials);
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        }

        $this->client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($this->client->getAccessToken()));
        }
        return $this->client;
    }

    public function googleUploadFile(string $name, string $full_path)
    {
        $this->client = $this->setToken();

        $service = new Google_Service_Drive($this->client);
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $name));
        $content = file_get_contents($full_path);

        $service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => '*/*',
            'uploadType' => 'multipart',
            'fields' => 'id'));
    }

}
