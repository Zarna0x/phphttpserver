<?php

namespace Zarna0x\WebServer;

class Request
{

    protected $method;
    protected $uri;
    protected $params = [];
    protected $headers = [];

    public function __construct($method, $uri, $headers = [])
    {
        $this->headers = $headers;
        $this->method = $method;

        @list( $this->uri, $params ) = explode('?', $uri);

        parse_str($params, $this->params);
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function getAllHeders()
    {
        return $this->headers;
    }
    
    public function getHeader($key, $default = '')
    {
        if ( !array_key_exists($key, $this->headers) ) {
            return $this->headers[$default];
        }
        
        return $this->headers[$key];
        
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getParam($key, $default = '')
    {
        if ( !array_key_exists($key, $this->params) ) {
            return $this->params[$default];
        }
        
        return $this->params[$key];
        
    }

    public static function withHeaders($header)
    {

        $lines = explode("\n", $header);

        // extract the method and uri
        list( $method, $uri ) = explode(' ', array_shift($lines));


        $headers = [];

        foreach ($lines as $line) {
            // clean the line
            $line = trim($line);

            if (strpos($line, ': ') !== false) {
                list( $key, $value ) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return new static($method, $uri, $headers);
    }
    
    

}
