<?php namespace OtherCode\Rest\Modules\Decoders;

/**
 * Class XMLDecoder
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @package OtherCode\Rest\Modules\Decoders
 */
class XMLDecoder extends \OtherCode\Rest\Modules\Decoders\BaseDecoder
{

    /**
     * The content type that trigger the decoder
     * @var string
     */
    protected $contentType = 'application/xml';

    /**
     * Decode the data of a request
     * @throws \OtherCode\Rest\Exceptions\DecodeException
     */
    public function decode()
    {
        /**
         * Preform the actual decode
         */
        $this->body = new \SimpleXMLElement($this->body);

        /**
         * Check errors
         */
        $errors = libxml_get_errors();
        if (isset($errors['code']) && $errors['code'] !== 0) {
            throw new \OtherCode\Rest\Exceptions\DecodeException($errors['message'], $errors['code']);
        }
    }
}