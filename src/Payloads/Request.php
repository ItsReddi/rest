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
     * @var Uri
     */
    public $uri;

    /**
     * HTTP Protocol version
     * @var string
     */
    public $version;

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
     * @param string $version
     */
    public function __construct($method = null, $uri = null, $body = null, \OtherCode\Rest\Payloads\Headers $headers = null, $version = '1.1')
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->body = $body;
        $this->version = $version;

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

    /**
     * Retrieves the message's request target.
     * @return string
     */
    public function getRequestTarget()
    {
        $target = $this->uri->getPath();
        if (empty($target)) {
            $target = '/';
        }

        if ($this->uri->getQuery() != '') {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * @param mixed $requestTarget
     * @return Request
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new \InvalidArgumentException('Invalid request target provided; cannot contain whitespace');
        }

        $request = clone $this;
        $request->requestTarget = $requestTarget;
        return $request;
    }

    /**
     * Retrieves the HTTP method of the request.
     * @return string Returns the request method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     * @param string $method
     * @throws \InvalidArgumentException
     * @return Request
     */
    public function withMethod($method)
    {
        if (!is_string($method)) {
            throw new \InvalidArgumentException('Method must be a string, ' . gettype($method) . ' given.');
        }

        $method = strtoupper($method);

        if (!in_array($method, array('POST', 'PUT', 'PATCH', 'DELETE', 'GET', 'HEAD'))) {
            throw new \InvalidArgumentException();
        }

        $request = clone $this;
        $request->method = $method;

        return $request;
    }

    /**
     * Retrieves the URI instance
     * @return Uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     * @param \Psr\Http\Message\UriInterface $uri
     * @param bool $preserveHost
     * @return $this|Request
     */
    public function withUri(\Psr\Http\Message\UriInterface $uri, $preserveHost = false)
    {
        if ($uri === $this->uri) {
            return $this;
        }

        $request = clone $this;
        $request->uri = $uri;

        if ($preserveHost === false) {
            $host = $this->uri->getHost();

            if (($port = $this->uri->getPort()) !== null) {
                $host .= ':' . $port;
            }

            if (isset($this->headers['host'])) {
                $header = $this->headers['host'];
            } else {
                $header = 'Host';
                $this->headers['host'] = 'Host';
            }

            $this->headers = array($header => array($host)) + $this->headers;
        }

        return $request;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     * @param string $version
     * @return $this|Request
     */
    public function withProtocolVersion($version)
    {
        if ($this->version === $version) {
            return $this;
        }

        $request = clone $this;
        $request->version = $version;

        return $request;
    }


    public function getHeaders()
    {

    }

    public function hasHeader($name)
    {
    }

    public function getHeader($name)
    {
    }

    public function getHeaderLine($name)
    {
    }

    public function withHeader($name, $value)
    {
    }

    public function withAddedHeader($name, $value)
    {
    }

    public function withoutHeader($name)
    {
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
    }
}