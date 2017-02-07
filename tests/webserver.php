<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory as SymfonyRequestConverter;
use Psr\Http\Message\RequestInterface;


$request = produceRequest();
$requestDetails = generateRequestJson($request);
echo $requestDetails;


function produceRequest() : RequestInterface
{
    $symfonyRequest = SymfonyRequest::createFromGlobals();
    $symfonyRequestConverter = new SymfonyRequestConverter();
    return $symfonyRequestConverter->createRequest($symfonyRequest);
}


function generateRequestJson(RequestInterface $request) : string
{
    $requestDetails = [
        'uri' => (string) $request->getUri(),
        'headers' => [
            'user-agent' => $request->getHeader('User-Agent')[0]
        ],
        'cookies' => $_COOKIE
    ];

    return json_encode($requestDetails);
}
