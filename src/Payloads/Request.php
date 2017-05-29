<?php

namespace OtherCode\Rest\Payloads;

/**
 * Class Request
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.0
 * @package OtherCode\Rest\Payloads
 */
class Request extends Message implements \Psr\Http\Message\RequestInterface
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
        $this->version = $version;

        $this->setBody($body);
        $this->setHeaders($headers);
    }

    /**
     * Set the Body
     * @param \Psr\Http\Message\StreamInterface $body
     */
    public function setBody(\Psr\Http\Message\StreamInterface $body = null)
    {
        if (isset($body)) {
            $this->body = $body;
        } else {
            $this->body = new \OtherCode\Rest\Payloads\Stream();
        }
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

}