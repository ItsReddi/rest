<?php

namespace OtherCode\Rest\Payloads;


/**
 * Stream Class
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.0
 * @package OtherCode\Rest\Payloads
 */
class Stream implements \Psr\Http\Message\StreamInterface
{

    /**
     * Main stream resource
     * @var resource
     */
    private $stream;

    /**
     * Stream size
     * @var mixed
     */
    private $size;

    /**
     * Is seekable
     * @var bool
     */
    private $seekable;

    /**
     * Is readable
     * @var bool
     */
    private $readable;

    /**
     * Is writable
     * @var bool
     */
    private $writable;

    /**
     * Stream resource uri
     * @var array|mixed|null
     */
    private $uri;

    /**
     * Custom metadata
     * @var array
     */
    private $customMetadata = array();

    /**
     * Hash of readable and writable stream types
     * @var array
     */
    private $modes = array(
        'read' => array(
            'r' => true, 'w+' => true, 'r+' => true, 'x+' => true, 'c+' => true,
            'rb' => true, 'w+b' => true, 'r+b' => true, 'x+b' => true,
            'c+b' => true, 'rt' => true, 'w+t' => true, 'r+t' => true,
            'x+t' => true, 'c+t' => true, 'a+' => true
        ),
        'write' => array(
            'w' => true, 'w+' => true, 'rw' => true, 'r+' => true, 'x+' => true,
            'c+' => true, 'wb' => true, 'w+b' => true, 'r+b' => true,
            'x+b' => true, 'c+b' => true, 'w+t' => true, 'r+t' => true,
            'x+t' => true, 'c+t' => true, 'a' => true, 'a+' => true
        )
    );

    /**
     * Stream constructor.
     * @param mixed $resource
     * @param array $options
     */
    public function __construct($resource = null, $options = array())
    {
        $this->stream = $this->createResource($resource);
        $meta = stream_get_meta_data($this->stream);

        if (isset($options['size'])) {
            $this->size = $options['size'];
        }

        if (isset($options['metadata'])) {
            $this->customMetadata = $options['metadata'];
        }

        $this->seekable = $meta['seekable'];
        $this->readable = isset($this->modes['read'][$meta['mode']]);
        $this->writable = isset($this->modes['write'][$meta['mode']]);

        $this->uri = $this->getMetadata('uri');
    }

    /**
     * Create a resource from the input data.
     * @param mixed $resource
     * @return resource
     * @throws \InvalidArgumentException
     */
    private function createResource($resource)
    {
        if (is_resource($resource)) {
            return $resource;
        }

        if (is_object($resource)) {
            if (method_exists($resource, '__toString')) {
                $resource = (string)$resource;
            }
        }

        if (is_scalar($resource) || is_null($resource)) {
            $stream = fopen('php://temp', 'r+');
            if ($resource !== '' && $resource !== null) {
                fwrite($stream, $resource);
                fseek($stream, 0);
            }
            return $stream;
        }

        throw new \InvalidArgumentException('Invalid resource type: ' . gettype($resource));
    }

    /**
     * Return the stream contents
     * @return bool|string
     */
    public function getContents()
    {
        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new \RuntimeException('Unable to read stream contents');
        }

        return $contents;
    }

    /**
     * Return the size content
     * @return mixed|null
     */
    public function getSize()
    {
        if ($this->size !== null) {
            return $this->size;
        }

        if (!isset($this->stream)) {
            return null;
        }

        if ($this->uri) {
            clearstatcache(true, $this->uri);
        }

        $stats = fstat($this->stream);

        if (isset($stats['size'])) {
            $this->size = $stats['size'];
            return $this->size;
        }

        return null;
    }

    /**
     * True if is readable
     * @return bool
     */
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * True if is writable
     * @return bool
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * True if is seekable
     * @return bool
     */
    public function isSeekable()
    {
        return $this->seekable;
    }

    /**
     * True if the pointer is at the stream
     * @return bool
     */
    public function eof()
    {
        return !$this->stream || feof($this->stream);
    }

    /**
     * Returns the current position of the file read/write pointer
     * @return bool|int
     */
    public function tell()
    {
        $result = ftell($this->stream);

        if ($result === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    /**
     * Return the pointer at the beginning of the stream
     * @return void
     */
    public function rewind()
    {
        $this->seek(0);
    }

    /**
     * Seeks on a file pointer
     * @param int $offset
     * @param int $whence
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->seekable) {
            throw new \RuntimeException('Stream is not seekable');

        } elseif (fseek($this->stream, $offset, $whence) === -1) {
            throw new \RuntimeException('Unable to seek to stream position ' . $offset . ' with whence ' . var_export($whence, true));
        }
    }

    /**
     * Binary-safe stream read
     * @param int $length
     * @return bool|string
     */
    public function read($length)
    {
        if (!$this->readable) {
            throw new \RuntimeException('Cannot read from non-readable stream');
        }
        if ($length < 0) {
            throw new \RuntimeException('Length parameter cannot be negative');
        }

        if (0 === $length) {
            return '';
        }

        $string = fread($this->stream, $length);
        if (false === $string) {
            throw new \RuntimeException('Unable to read from stream');
        }

        return $string;
    }

    /**
     * Binary-safe stream write
     * @param string $string
     * @return bool|int
     */
    public function write($string)
    {
        if (!$this->writable) {
            throw new \RuntimeException('Cannot write to a non-writable stream');
        }

        $this->size = null;
        $result = fwrite($this->stream, $string);

        if ($result === false) {
            throw new \RuntimeException('Unable to write to stream');
        }

        return $result;
    }

    /**
     * Return the requested metadata
     * @param null $key
     * @return array|mixed|null
     */
    public function getMetadata($key = null)
    {
        if (!isset($key)) {
            return $this->customMetadata + stream_get_meta_data($this->stream);
        }

        if (isset($this->customMetadata[$key])) {
            return $this->customMetadata[$key];
        }

        $meta = stream_get_meta_data($this->stream);
        if (isset($meta[$key])) {
            return $meta[$key];
        }

        return null;
    }

    /**
     * Close the stream
     */
    public function close()
    {
        if (isset($this->stream)) {
            if (is_resource($this->stream)) {
                fclose($this->stream);
            }
            $this->detach();
        }
    }

    /**
     * Detach the current stream
     * @return null|resource
     */
    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $result = $this->stream;

        unset($this->stream);

        $this->size = $this->uri = null;
        $this->readable = $this->writable = $this->seekable = false;

        return $result;
    }

    /**
     * Transform the stream to string
     * @return string
     */
    public function __toString()
    {
        try {
            $this->seek(0);
            return (string)stream_get_contents($this->stream);

        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Destroy the stream object
     */
    public function __destruct()
    {
        $this->close();
    }
}
