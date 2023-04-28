<?php

namespace Will1471\HttpExpect;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class Expectations
{
    /**
     * @var list<Expectation>
     */
    private array $expectations = [];

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function push(Expectation $expectation): void
    {
        $this->logger->debug('Adding expectation', ['expectation' => ['path' => $expectation->path]]);
        $this->expectations[] = $expectation;
    }

    public function reset(): void
    {
        $this->expectations = [];
    }

    public function matching(ServerRequestInterface $request): ?Expectation
    {
        foreach ($this->expectations as $expectation) {
            if ($expectation->matches($request)) {
                return $expectation;
            }
        }
        return null;
    }
}