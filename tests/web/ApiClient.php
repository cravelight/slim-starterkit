<?php

namespace SlimStartkitTests;

use Cravelight\Slim\Hmac\HmacHelper;

/**
 * Class ApiClient
 *
 * Quick and dirty cURL based api client.
 *
 * todo: consider moving to Guzzle for a more robust implementation (i.e. http status codes, etc)
 * http://docs.guzzlephp.org/en/latest/
 *
 */
class ApiClient
{
    protected $url;
    protected $key;
    protected $hmacHelper;

    /**
     * Constructor
     *
     * @param type $apiBaseUrl Base url to the API (no trailing slash)
     * @param type $apiPublicKey Your public API Key
     * @param type $apiSecret Your Secret (private hashing key)
     */
    public function __construct($apiBaseUrl, $apiPublicKey, $apiSecret)
    {
        $this->url = $apiBaseUrl;
        $this->key = $apiPublicKey;
        $this->hmacHelper = new HmacHelper($apiSecret);
    }


    public function Ping()
    {
        $endpoint = '/ping';
        return $this->executeGetRequest($endpoint);
    }





    protected function executePostRequest(string $endpointWithQuery, array $postdata)
    {
        $curl = $this->getCurlHandle($endpointWithQuery, 'POST');

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

        $curl_response = curl_exec($curl);
        curl_close($curl);

        return $curl_response;
    }

    protected function executeGetRequest(string $endpointWithQuery)
    {
        $curl = $this->getCurlHandle($endpointWithQuery, 'GET');

        $curl_response = curl_exec($curl);
        curl_close($curl);

        return $curl_response;
    }

    protected function getCurlHandle(string $endpointWithQuery, string $method)
    {
        $timestamp = time();
        $url = $this->buildUrl($endpointWithQuery);
        $hash = $this->hmacHelper->getHashFor($url, $timestamp, $method);
        $customHeader = $this->hmacHelper->getCustomHeaderArrayFor($timestamp, $hash);

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 400); //timeout in seconds
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER ,false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $customHeader);

        return $curl;
    }

    protected function buildUrl($endpointWithQuery)
    {
        return $this->url . '/' . $this->key . $endpointWithQuery;
    }

}
