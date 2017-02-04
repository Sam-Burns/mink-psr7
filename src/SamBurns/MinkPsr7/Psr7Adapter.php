<?php

namespace SamBurns\MinkPsr7;

use Behat\Mink\Driver\DriverInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class Psr7Adapter
{
    /** @var DriverInterface */
    private $minkDriver;

    public function __construct(DriverInterface $minkDriver)
    {
        $this->minkDriver = $minkDriver;
    }

    public function doRequest(ServerRequestInterface $request) : ResponseInterface
    {
        $this->provideRequestToMinkDriver($request);
        $url = $request->getUri();
        $this->tellMinkDriverToSendRequest($url);
        return $this->buildResponseFromMinkDriver();
    }

    private function provideRequestToMinkDriver(ServerRequestInterface $request)
    {
        foreach ($request->getHeaders() as $headerKey => $headerValue) {
            $this->minkDriver->setRequestHeader($headerKey, $headerValue);
        }

        foreach ($request->getCookieParams() as $cookieKey => $cookieValue) {
            $this->minkDriver->setCookie($cookieKey, $cookieValue);
        }
    }

    private function tellMinkDriverToSendRequest($url)
    {
        $this->minkDriver->visit($url);
    }

    private function buildResponseFromMinkDriver() : ResponseInterface
    {
        $responseBody = $this->minkDriver->getContent();
        $responseStatusCode = $this->minkDriver->getStatusCode();
        $responseHeaders = $this->minkDriver->getResponseHeaders();

        $stream = $this->buildStreamWithBody($responseBody);

        $response = new Response($stream, $responseStatusCode, $responseHeaders);
        return $response;
    }

    private function buildStreamWithBody(string $streamBody) : Stream
    {
        $streamResource = fopen('php://memory','r+');
        fwrite($streamResource, $streamBody);
        rewind($streamResource);
        return new Stream($streamResource);
    }
}
