<?php

namespace Will1471\HttpExpect\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Expectation
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly ResponseInterface $response
    ) {
    }

    public function matches(ServerRequestInterface $request): bool
    {
        return strtolower($this->method) == strtolower($request->getMethod())
            && $this->path == $request->getUri()->getPath();
    }
}
