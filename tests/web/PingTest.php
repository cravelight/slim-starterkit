<?php require 'bootstrap.php';

use SlimStartkitTests\ApiClient;


class PingTest extends PHPUnit_Framework_TestCase
{
    protected $key;
    protected $secret;
    protected $url;

    protected function setUp()
    {
        $this->key = 1234567890;
        $this->secret = API_TEST_SECRET;
        $this->url = API_TEST_URL;
    }


    public function testPingSuccess()
    {
        $client = new ApiClient($this->url, $this->key, $this->secret);
        $result = $client->Ping();

        $this->assertEquals('{"message":"pong"}', $result);
    }

    public function testPingFailureBadSecret()
    {
        $client = new ApiClient($this->url, $this->key, 'a really bad secret');
        $result = $client->Ping();

        $this->assertEquals('{"message":"Say goodnight, Gracie."}', $result);
        //todo: Guzzle might let us check for the 401 http status code
    }

}
