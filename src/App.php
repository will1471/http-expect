<?php

namespace Will1471\HttpExpect;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use React\Socket\SocketServer;

abstract class App
{
    public static function start(string $proxyAddress, string $apiAddress): void
    {
        $logger = new Logger('http-expect', [new StreamHandler('php://stdout')]);

        $loop = Loop::get();

        $expect = new Expectations($logger);

        $httpProxy = (new HttpServer(new ProxyServer($expect, $logger)));
        $httpApi = (new HttpServer(new ApiServer($expect, $logger)));

        $httpProxy->on('error', function (\Exception $e) use ($logger) {
            $logger->error('Error on proxy server', ['exception' => $e]);
        });

        $httpProxy->listen(new SocketServer($proxyAddress, [], $loop));
        $httpApi->listen(new SocketServer($apiAddress, [], $loop));

        $logger->debug('started...');
    }
}
