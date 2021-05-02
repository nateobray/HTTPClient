<?php 
namespace obray\handlers;

class HTTPClientHandler implements \obray\interfaces\SocketClientHandlerInterface
{
    private $host;
    private $port;
    private $scheme;
    private $method = \obray\http\Methods::GET;
    private $headers;
    private $uri;
    private $client;
    private $body;

    private $response;

    public function __construct(string $method=\obray\http\Methods::GET, string $url, \obray\http\Headers $headers=null, string $body=null)
    {
        $components = parse_url($url);
        if(empty($components['host'])) throw new \Exception("Must specify a host in your supplied url.");
        $this->scheme = $components['scheme'];
        $this->host = $components['host'];
        $this->port = $components['port']?? 80;
        $this->uri = $components['path']??'/';
        
        $this->method = $method;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function onStart(\obray\SocketServer $connection): void
    {
        print_r("Started client\n");
        $this->client = $connection;
    }

    public function onStartClient(\obray\SocketClient $connection): void
    {
        print_r("Started client\n");
        $this->client = $connection;
    }

    public function onConnect(\obray\interfaces\SocketConnectionInterface $connection): void
    {
        print_r("Connecting...");
    }

    public function onConnected(\obray\interfaces\SocketConnectionInterface $connection): void
    {
        print_r("success\n");
        if(empty($this->headers)) $this->headers = new \obray\http\Headers([]);
        
        $this->headers->addHeader(new \obray\http\Header('Host', $this->host));
        $request = new \obray\http\Request($this->method, $this->uri, 'HTTP/1.1', $this->headers);
        if(!empty($this->body)) $request->setBody($body);
        $connection->qWrite($request->encode());
    }

    public function onConnectFailed(\obray\interfaces\SocketConnectionInterface $connection): void
    {
        print_r("failed\n");
    }

    public function onData(string $data, \obray\interfaces\SocketConnectionInterface $connection): void
    {
        $this->response = \obray\http\Transport::decode($data);
        if($this->response->isComplete()) $this->client->stop();
    }

    public function onWriteFailed($data, \obray\interfaces\SocketConnectionInterface $connection): void
    {
        print_r("Write to socket failed\n");
    }

    public function onReadFailed(\obray\interfaces\SocketConnectionInterface $connection): void
    {
        print_r("Read from socket failed\n");
    }

    public function onDisconnect(\obray\interfaces\SocketConnectionInterface $connection): void
    {
        print_r("Disconnecting...");
    }

    public function onDisconnected(\obray\interfaces\SocketConnectionInterface $connection): void
    {
        print_r("Success\n");
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getURI(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getResponse(): \obray\http\Transport
    {
        return $this->response;
    }
}