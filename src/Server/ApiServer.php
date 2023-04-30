<?php

namespace Will1471\HttpExpect\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use React\Http\Message\Response;

final class ApiServer
{
    public function __construct(private readonly Expectations $expectations, private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->debug(
            'Got request on api server',
            [
                'method' => $method = $request->getMethod(),
                'path' => $path = $request->getUri()->getPath(),
                'body' => $body = $request->getBody()->getContents()
            ]
        );

        if ($method == 'DELETE' && $path = '/expect') {
            $this->expectations->reset();
            return Response::json(['success' => true])->withStatus(200);
        }

        if ($method == 'POST' && $path == '/expect' && $request->getHeaderLine('Content-Type') == 'application/json') {
            $data = json_decode($body);
            $this->expectations->push(
                new Expectation(
                    $data->request->method,
                    $data->request->path,
                    Response::plaintext($data->response->body)
                )
            );
            return Response::json(['success' => true])->withStatus(201);
        }

        return Response::json(['success' => false])->withStatus(400);
    }
}