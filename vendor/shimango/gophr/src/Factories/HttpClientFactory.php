<?php

namespace Shimango\Gophr\Factories;

use GuzzleHttp\Client;

final class HttpClientFactory
{
    /**
     * Creates a new Guzzle client. To allow for user flexibility, the client is not reused. This allows the user to set
     * and change headers on a per-request basis. If a proxyPort is passed, all requests will be sent through this proxy.
     * @return Client The new client
     */
    public static function makeGuzzleClient(string $baseUrl, array $headers, bool $http_errors = false, bool $proxyVerifySSL = false, ?int $proxyPort = null): Client
    {
        $clientSettings = [
            'base_uri' =>  $baseUrl,
            'http_errors' => $http_errors,
            'headers' => $headers
        ];

        if ($proxyPort !== null) {
            $clientSettings['verify'] = $proxyVerifySSL;
            $clientSettings['proxy'] = $proxyPort;
        }

        if (extension_loaded('curl') && defined('CURL_VERSION_HTTP2') && (curl_version()["features"] & CURL_VERSION_HTTP2 !== 0)) {

            // Enable HTTP/2 if curl lib exists and supports it
            $clientSettings['version'] = '2';
        }

        return new Client($clientSettings);
    }
}
