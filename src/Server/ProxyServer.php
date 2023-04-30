<?php

namespace Will1471\HttpExpect\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use React\Http\Message\Response;

final class ProxyServer
{
    public function __construct(
        private readonly Expectations $expectations,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->debug('Got request on proxy server', [
            'method' => $request->getMethod(),
            'path' => $request->getUri()->getPath()
        ]);

        $response = $this->expectations->matching($request)?->response;
        if ($response) {
            $this->logger->debug('Returning expected response');
            return clone $response;
        }

        $this->logger->error('Request not expected');
        return Response::plaintext("Not expected\n")->withStatus(500);
    }
}
