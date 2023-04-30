<?php

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/test', function (ServerRequestInterface $request, ResponseInterface $response) {
    // HTTP_PROXY may get set from Http Header "Proxy" in some cases... see https://httpoxy.org/
    // Guzzle only uses HTTP_PROXY env by default on cli env because of this...

    $client = new Client(array_filter(['proxy' => getenv('HTTP_PROXY')]));

    $response->getBody()->write(
        $client->get('http://example.com')->getBody()->getContents()
    );

    return $response;
});

$app->run();