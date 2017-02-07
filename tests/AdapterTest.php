<?php

use PHPUnit\Framework\TestCase;
use Behat\Mink\Driver\GoutteDriver;
use SamBurns\MinkPsr7\Psr7Adapter;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AdapterTest extends TestCase
{
    /** @var GoutteDriver */
    private $goutteDriver;

    /** @var Psr7Adapter */
    private $psr7Adapter;

    /** @var ServerRequestInterface */
    private $request;

    public function setUp()
    {
        $this->goutteDriver = new GoutteDriver();

        $this->psr7Adapter = new Psr7Adapter($this->goutteDriver);

        $this->request = (new ServerRequest())->withUri(new Uri('http://localhost:8089/'));
    }

    public function testAResponseIsReturned()
    {
        $response = $this->psr7Adapter->doRequest($this->request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testRequestedUriIsCorrect()
    {
        // ARRANGE
        $request = $this->request->withUri(new Uri('http://localhost:8089/something'));

        // ACT
        $response = $this->psr7Adapter->doRequest($request);

        // ASSERT
        $uriRequested = $this->getResponseBodyArray($response)['uri'];
        $this->assertEquals('http://localhost:8089/something', $uriRequested);
    }

    public function testRequestHeadersAreSent()
    {
        // ARRANGE
        $exampleUserAgent = 'Mozilla/5.0 (Android 4.4; Tablet; rv:41.0) Gecko/41.0 Firefox/41.0';
        $request = $this->request->withHeader('User-Agent', $exampleUserAgent);

        // ACT
        $response = $this->psr7Adapter->doRequest($request);

        // ASSERT
        $userAgentInRequest = $this->getResponseBodyArray($response)['headers']['user-agent'];
        $this->assertEquals($exampleUserAgent, $userAgentInRequest);
    }

    public function testCookiesAreSent()
    {
        // ARRANGE
        $request = $this->request->withCookieParams(['cookie-key' => 'cookie-value']);

        // ACT
        $response = $this->psr7Adapter->doRequest($request);

        // ASSERT
        $cookieValueSent = $this->getResponseBodyArray($response)['cookies']['cookie-key'];
        $this->assertEquals('cookie-value', $cookieValueSent);
    }

    private function getResponseBodyArray(ResponseInterface $response) : array
    {
        $responseBody = $response->getBody()->getContents();
        return json_decode($responseBody, true);
    }
}
