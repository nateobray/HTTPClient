<?php
namespace obray;

class HTTPClient
{
    static public function get(string $url, array $headers=null): \obray\http\Transport
    {
        $handler = new \obray\handlers\HTTPClientHandler(\obray\http\Methods::GET, $url, $headers);
        $client = new \obray\SocketClient('tcp', $handler->getHost(), $handler->getPort(), new \obray\StreamContext());
        $client->connect($handler);

        return $handler->getResponse();
    }

    static public function post(string $url, string $body, array $headers=null): \obray\http\Transport
    {
        $handler = new \obray\handlers\HTTPClientHandler(\obray\http\Methods::POST, $url, $headers, $body);
        $client = new \obray\SocketClient('tcp', $handler->getHost(), $handler->getPort(), new \obray\StreamContext());
        $client->connect($handler);

        return $handler->getResponse();
    }
}