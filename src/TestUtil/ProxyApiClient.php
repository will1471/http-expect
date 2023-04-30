<?php

namespace Will1471\HttpExpect\TestUtil;

use GuzzleHttp\Client;

final class ProxyApiClient
{
    private Client $client;

    public function __construct(string $address)
    {
        $this->client = new Client(['base_uri' => $address]);
    }

    public function expect(string $method, string $path): ExpectResponseInterface
    {
        $requestSpec = ['method' => $method, 'path' => $path];
        return new class ($this->client, $requestSpec) implements ExpectResponseInterface {
            public function __construct(private readonly Client $client, private readonly array $requestSpec)
            {
            }

            public function toReturnBody(string $body): void
            {
                $this->client->post(
                    '/expect',
                    ['json' => ['request' => $this->requestSpec, 'response' => ['body' => $body]]]
                );
            }
        };
    }

    public function reset()
    {
        $this->client->delete('/expect');
    }
}
