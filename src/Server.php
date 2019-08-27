<?php

namespace Zarna0x\WebServer;

class Server
{

    private $socket;
    private $host;
    private $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->connect();
    }

    protected function connect()
    {
        $this->makeSocket();
        $this->bind();
    }

    private function makeSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
    }

    private function bind()
    {
        if (!socket_bind($this->socket, $this->host, $this->port)) {
            throw new \Exception('Could not bind: ' .
            $this->host . ':' . $this->port . ' - ' . socket_strerror(socket_last_error()));
        }
    }

    public function listen(callable $callback)
    {
        while (true) {
            socket_listen($this->socket);

            if (!$client = socket_accept($this->socket)) {
                echo socket_strerror($client);
                socket_close($client);
                continue;
            }


            $request = Request::withHeaders(socket_read($client, 1024000));



            $response = call_user_func($callback, $request);
            $socketMessage = (string) $response;
        
            socket_write($client, $socketMessage, strlen($socketMessage));
      
            socket_close($client);
        }
    }

}

//GET / HTTP/1.1  
//Host: 127.0.0.1:8008  
//Connection: keep-alive  
//Accept: text/html  
//User-Agent: Chrome/41.0.2272.104  
//Accept-Encoding: gzip, deflate, sdch  
//Accept-Language: en-US,en;q=0.8,de;q=0.6 