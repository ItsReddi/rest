<?php namespace OtherCode\Rest\Modules\Decoders;

/**
 * Class BaseDecoder
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.0
 * @package OtherCode\Rest\Modules\Decoders
 * @property int $code
 * @property string $content_type
 * @property string $charset
 * @property \OtherCode\Rest\Payloads\Stream $body
 * @property array $metadata
 */
abstract class BaseDecoder extends \OtherCode\Rest\Modules\BaseModule
{
    /**
     * The content type that trigger the decoder
     * @var string
     */
    protected $contentType = 'text/plain';

    /**
     * Perform the main decode of the data
     */
    protected function decode()
    {
        // do something with $this->body
    }

    /**
     * Run the main decode method
     */
    public function run()
    {
        /**
         * match the content type and run the decoder
         */
        if ($this->contentType == $this->content_type) {
            $this->decode();
        }
        return true;
    }
}