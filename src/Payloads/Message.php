<?php

namespace OtherCode\Rest\Payloads;

/**
 * Message Class
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.0
 * @package OtherCode\Rest\Payloads
 */
abstract class Message implements \Psr\Http\Message\MessageInterface
{

    /**
     * Protocol version
     * @var string
     */
    public $version = '1.1';

    /**
     * The response headers
     * @var \OtherCode\Rest\Payloads\Headers
     */
    public $headers;

    /**
     * Main body for the message
     * @var \Psr\Http\Message\StreamInterface
     */
    public $body;

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

    /**
     * @return Headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader($header)
    {
        return isset($this->headerNames[strtolower($header)]);
    }

    /**
     * @param string $header
     * @return array|mixed
     */
    public function getHeader($header)
    {
        $header = strtolower($header);

        if (!isset($this->headerNames[$header])) {
            return [];
        }

        $header = $this->headerNames[$header];

        return $this->headers[$header];
    }

    /**
     * @param string $header
     * @return string
     */
    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * @param string $header
     * @param string|\string[] $value
     * @return Message
     */
    public function withHeader($header, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $value = $this->trimHeaderValues($value);
        $normalized = strtolower($header);

        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $header;
        $new->headers[$header] = $value;

        return $new;
    }

    /**
     * @param string $header
     * @param string|\string[] $value
     * @return Message
     */
    public function withAddedHeader($header, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $value = $this->trimHeaderValues($value);
        $normalized = strtolower($header);

        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            $header = $this->headerNames[$normalized];
            $new->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $new->headerNames[$normalized] = $header;
            $new->headers[$header] = $value;
        }

        return $new;
    }

    /**
     * @param string $header
     * @return $this|Message
     */
    public function withoutHeader($header)
    {
        $normalized = strtolower($header);

        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }

        $header = $this->headerNames[$normalized];

        $new = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);

        return $new;
    }

    /**
     * Return the message body
     * @return Stream|\Psr\Http\Message\StreamInterface|string
     */
    public function getBody()
    {
        if (!$this->stream) {
            $this->stream = new \OtherCode\Rest\Payloads\Stream();
        }

        return $this->stream;
    }

    /**
     * Return the current Message with the new body
     * @param \Psr\Http\Message\StreamInterface $body
     * @return $this|Message
     */
    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
        if ($body === $this->stream) {
            return $this;
        }

        $new = clone $this;
        $new->stream = $body;
        return $new;
    }

}