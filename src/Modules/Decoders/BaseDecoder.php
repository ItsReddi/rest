<?php

namespace OtherCode\Rest\Modules\Decoders;

/**
 * Class BaseDecoder
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.0
 * @package OtherCode\Rest\Modules\Decoders
 */
abstract class BaseDecoder extends \OtherCode\Rest\Modules\BaseModule implements \OtherCode\Rest\Modules\Decoders\DecoderInterface
{
    /**
     * The content type that trigger the decoder
     * @var string
     */
    protected $contentType = 'text/plain';

    /**
     * Run the main decode method
     */
    public function run()
    {
        /**
         * First we check if the response
         * has any error.
         */
        if ($this->error->code != 0) {
            return false;
        }

        $body = $this->body;
        $content_type = $this->content_type;
        if (!empty($body) && isset($content_type)) {

            /**
             * match the content type and run the decoder
             */
            if ($this->contentType == $content_type) {
                $this->decode();
            }
        }

        return true;
    }
}