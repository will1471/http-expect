<?php

namespace Will1471\HttpExpectExample\Test;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Will1471\HttpExpect\TestUtil\ProxyApiClient;

class ProxyTest extends TestCase
{
    private ProxyApiClient $proxyAPI;
    private Client $appClient;

    public function setUp(): void
    {
        $this->proxyAPI = new ProxyApiClient('http://localhost:8082/');
        $this->appClient = new Client(['base_uri' => 'http://localhost:8080/']);
    }

    public function tearDown(): void
    {
        // clear previous expectations
        $this->proxyAPI->reset();
    }

    public function testItCanProxy(): void
    {
        $key = 'SomeRandomKey' . bin2hex(random_bytes(4));

        // expect a call to /
        $this->proxyAPI->expect('GET', '/')->toReturnBody('Different Response: ' . $key);

        // call the /test endpoint on slim app, which would normally return http://example.com contents
        $this->assertStringContainsString($key, $this->appClient->get('test')->getBody()->getContents());
    }
}
