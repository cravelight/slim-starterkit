<?php

namespace Cravelight\Slim\Hmac;


use Slim\Http\Request;


/**
 * Helper class to prepare and validate HMAC tokens
 *
 * Notes:
 * * uses sha256
 * * enforces a 300 second timeout (5 minutes)
 *
 * Required Headers
 * * $prefix-hash: the hash value
 * * $prefix-time: the timestamp of the request
 *
 * Hash calculated as:
 * * $data = timestamp . http_method . url_with_query_string;
 * * $hash = base64_encode(hash_hmac('sha256', $data, 'your_secret_here', true));
 *
 * @package Cravelight\Slim\HMAC
 */
class HmacHelper
{
    const HASH_HEADER_SUFFIX = '-hash';
    const TIME_HEADER_SUFFIX = '-time';


    private $secret;
    private $hashHeaderName;
    private $timeHeaderName;




    /**
     * HmacHelper constructor.
     * @param string $secret The private key used to create your hash
     * @param string $prefix A prefix used to ensure uniqueness in the time and hash http headers
     */
    public function __construct(string $secret, string $prefix = 'x-hmac')
    {
        $this->secret = $secret;
        $this->hashHeaderName = $prefix . self::HASH_HEADER_SUFFIX;
        $this->timeHeaderName = $prefix . self::TIME_HEADER_SUFFIX;
    }


    /**
     * Makes sure the message has not been tampered with and is not expired.
     *
     * @param Request $request
     * @return bool
     */
    public function messageAuthenticates(Request $request) : bool
    {

        if (!$this->checkTimestamp($request))
        {
            return false;
        }

        if (!$this->checkHash($request))
        {
            return false;
        }

        return true;

    }


    /**
     * Returns the HMAC hash for the given url, time and http method
     *
     * @param string $url the url you want to hash
     * @param int $timestamp your timestamp (seconds since unix epoch)
     * @param string $method http method you will use with the request
     * @return string base64 encoded sha256 hash value
     */
    public function getHashFor(string $url, int $timestamp, string $method = 'GET') : string
    {
        $data = $timestamp . $method . $url;
        return base64_encode(hash_hmac('sha256', $data, $this->secret, true));
    }


    public function getCustomHeaderArrayFor(string $timestamp, string $hash) : array
    {
        return array(
            $this->timeHeaderName . ': ' . $timestamp,
            $this->hashHeaderName . ': ' . $hash
        );
    }


    private function checkTimestamp(Request $request, $maxSeconds = 300) : bool
    {
        if (!$request->getHeader($this->timeHeaderName)) {
            return false;
        }
        $timestamp = $request->getHeader($this->timeHeaderName)[0];
        $timeDiff = time() - $timestamp;
        return $timeDiff <= $maxSeconds;
    }


    private function checkHash(Request $request) : bool
    {
        if (!$request->getHeader($this->hashHeaderName)) {
            return false;
        }
        $assertedHash = $request->getHeader($this->hashHeaderName)[0];


        $url = (string)$request->getUri();
        /**
         * Why cast url to string?
         *
         * From: http://www.php-fig.org/psr/psr-7/
         *
         * The effective URL is represented by UriInterface. UriInterface models HTTP and HTTPS URIs as specified in
         * RFC 3986 (the primary use case). The interface provides methods for interacting with the various URI parts,
         * which will obviate the need for repeated parsing of the URI. It also specifies a __toString() method for
         * casting the modeled URI to its string representation.
         */

        if (!$request->getHeader($this->timeHeaderName)) {
            return false;
        }
        $time = $request->getHeader($this->timeHeaderName)[0];


        $calculatedHash = $this->GetHashFor($url, $time, $request->getMethod());

        if ($assertedHash === $calculatedHash) {
            return true;
        }
        return false;
    }




}


