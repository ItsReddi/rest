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
     * The response headers
     * @var \OtherCode\Rest\Payloads\Headers
     */
    public $headers;

    /**
     * @var string
     */
    private $protocol = '1.1';

    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    private $stream;

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * @param string $version
     * @return $this|Message
     */
    public function withProtocolVersion($version)
    {
        if ($this->protocol === $version) {
            return $this;
        }

        $new = clone $this;
        $new->protocol = $version;
        return $new;
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
     * @return Stream|\Psr\Http\Message\StreamInterface|string
     */
    public function getBody()
    {
        if (!$this->stream) {
            $this->stream = stream_for('');
        }

        return $this->stream;
    }

    /**
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