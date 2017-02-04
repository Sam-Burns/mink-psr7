<?php

require_once __DIR__ . '/../vendor/autoload.php';

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$requestDetails = [
    'uri' => $request->getUri(),
];

echo json_encode($requestDetails);
