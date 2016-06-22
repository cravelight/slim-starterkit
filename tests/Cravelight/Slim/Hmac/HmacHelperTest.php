<?php

use Cravelight\Slim\Hmac\HmacHelper;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\UploadedFile;
use Slim\Http\Uri;


class HmacHelperTest extends PHPUnit_Framework_TestCase
{
    protected $secret;
    protected $timestamp;
    protected $prefix;
    protected $url;

    protected function setUp()
    {
        $this->secret = uniqid();
        $this->timestamp = time();
        $this->prefix = 'x-prefix';
        $this->url = 'http://www.example.com/some/other/path?foo=bar&bar=baz=1';
    }


    public function testCreateObject()
    {
        // Arrange|Given

        // Act|When
        $hmacHelper = $this->getHmacHelper();

        // Assert|Then
        $this->assertInstanceOf('Cravelight\Slim\Hmac\HmacHelper', $hmacHelper);
    }

    public function testCreateCustomHeaderArray()
    {
        // Arrange|Given
        $expectedHashHeaderKey = $this->prefix . '-hash';
        $expectedTimeHeaderKey = $this->prefix . '-time';
        $hmacHelper = $this->getHmacHelper();

        // Act|When
        $hash = $hmacHelper->getHashFor($this->url, $this->timestamp);
        $headers = $hmacHelper->getCustomHeaderArrayFor($this->timestamp, $hash);

        // Assert|Then
        $this->assertTrue(is_array($headers));

        $keyedHeaders = $this->getKeyedHeadersFromHttpHeaderArray($headers);
        $this->assertArrayHasKey($expectedHashHeaderKey, $keyedHeaders);
        $this->assertEquals($hash, $keyedHeaders[$expectedHashHeaderKey]);
        $this->assertArrayHasKey($expectedTimeHeaderKey, $keyedHeaders);
        $this->assertEquals($this->timestamp, $keyedHeaders[$expectedTimeHeaderKey]);

    }

    public function testMessageAuthSuccess()
    {
        // Arrange|Given
        $hmacHelper = $this->getHmacHelper();
        $request = $this->getSignedRequestObjectFor(
            $this->secret,
            $this->prefix,
            $this->url,
            $this->timestamp,
            'GET');

        // Act|When
        $result = $hmacHelper->messageAuthenticates($request);

        // Assert|Then
        $this->assertTrue($result);
    }

    public function testMessageAuthFailsBadSecret()
    {
        // Arrange|Given
        $hmacHelper = $this->getHmacHelper();
        $request = $this->getSignedRequestObjectFor(
            'this is a super bad secret',
            $this->prefix,
            $this->url,
            $this->timestamp,
            'GET');

        // Act|When
        $result = $hmacHelper->messageAuthenticates($request);

        // Assert|Then
        $this->assertFalse($result);
    }

    public function testMessageAuthFailsOldTimestamp()
    {
        // Arrange|Given
        $hmacHelper = $this->getHmacHelper();
        $request = $this->getSignedRequestObjectFor(
            $this->secret,
            $this->prefix,
            $this->url,
            $this->timestamp - (10 * 60), // 10 minutes old
            'GET');

        // Act|When
        $result = $hmacHelper->messageAuthenticates($request);

        // Assert|Then
        $this->assertFalse($result);
    }

    public function testMessageAuthFailsBadUrl()
    {
        // Arrange|Given
        $hmacHelper = $this->getHmacHelper();
        $request = $this->getSignedRequestObjectFor(
            $this->secret,
            $this->prefix,
            'http://www.a-bad-url-goes-here.com',
            $this->timestamp,
            'GET');

        // Act|When
        $result = $hmacHelper->messageAuthenticates($request);

        // Assert|Then
        $this->assertFalse($result);
    }

    public function testMessageAuthFailsMissingHeaders()
    {
        // Arrange|Given
        $hmacHelper = $this->getHmacHelper();
        $request = $this->getSignedRequestObjectFor(
            $this->secret,
            'unexpected-prefix-will-mismatch-headers',
            $this->url,
            $this->timestamp,
            'GET');

        // Act|When
        $result = $hmacHelper->messageAuthenticates($request);

        // Assert|Then
        $this->assertFalse($result);
    }










    private function getHmacHelper() : HmacHelper
    {
        return new HmacHelper($this->secret, $this->prefix);
    }

    private function getKeyedHeadersFromHttpHeaderArray(array $headers) : array
    {
        $keyedHeaders = array();
        array_walk($headers, function($header) use (&$keyedHeaders) {
            $parts = explode(': ', $header, 2);
            $keyedHeaders[$parts[0]] = $parts[1];
        });
        return $keyedHeaders;
    }

    private function getSignedRequestObjectFor($secret, $prefix, $url, $timestamp, $method) : Request
    {
        // concept from: https://github.com/slimphp/Slim/blob/3.x/tests/Http/RequestTest.php
        $env = Environment::mock([]);
        $uri = Uri::createFromString($url);
        $headers = Headers::createFromEnvironment($env);
        $cookies = [];
        $serverParams = $env->all();
        $body = new RequestBody();
        $uploadedFiles = UploadedFile::createFromEnvironment($env);

        $headers->add($prefix . '-time', $timestamp);
        $hmacHelper = new HmacHelper($secret, $prefix);
        $hash = $hmacHelper->getHashFor($url, $timestamp, $method);
        $headers->add($prefix . '-hash', $hash);

        $request = new Request($method, $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);
        return $request;
    }

}
