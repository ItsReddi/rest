<?php

namespace OtherCode\Rest\Payloads;

/**
 * Class Request
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.0
 * @package OtherCode\Rest\Payloads
 */
class Request implements \Psr\Http\Message\RequestInterface
{
    /**
     * Http Method
     * @var string
     */
    public $method;

    /**
     * Uri to call
     * @var string
     */
    public $uri;

    /**
     * Request headers
     * @var \OtherCode\Rest\Payloads\Headers
     */
    public $headers;

    /**
     * Main data to be send
     * @var array|object
     */
    public $body;

    /**
     * Request constructor.
     * @param string $method
     * @param string $uri
     * @param array|object $body
     * @param Headers|null $headers
     */
    public function __construct($method = null, $uri = null, $body = null, \OtherCode\Rest\Payloads\Headers $headers = null)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->body = $body;

        $this->setHeaders($headers);
    }

    /**
     * Set the headers
     * @param Headers $headers
     */
    public function setHeaders(\OtherCode\Rest\Payloads\Headers $headers = null)
    {
        if (isset($headers)) {
            $this->headers = $headers;
        } else {
            $this->headers = new \OtherCode\Rest\Payloads\Headers();
        }
    }

    public function getRequestTarget()
    {

    }

    public function withRequestTarget($requestTarget)
    {
    }

    /**
     * Retrieves the HTTP method of the request.
     * @return string Returns the request method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(\Psr\Http\Message\UriInterface $uri, $preserveHost = false)
    {
    }

    public function getProtocolVersion(){}

    public function withProtocolVersion($version){}

    public function getHeaders(){}

    public function hasHeader($name){}

    public function getHeader($name){}

    public function getHeaderLine($name){}

    public function withHeader($name, $value){}

    public function withAddedHeader($name, $value){}

    public function withoutHeader($name){}

    public function getBody(){}

    public function withBody(\Psr\Http\Message\StreamInterface $body){}

}