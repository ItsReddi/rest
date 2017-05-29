<?php

namespace OtherCode\Rest\Modules\Encoders;

/**
 * Class JSONEncoder
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @package OtherCode\Rest\Modules\Encoders
 */
class JSONEncoder extends \OtherCode\Rest\Modules\Encoders\BaseEncoder
{
    /**
     * Method
     * @var array
     */
    protected $methods = array('POST', 'PUT', 'PATCH');

    /**
     * create a xml rpc document based on the provided data.
     * @throws \OtherCode\Rest\Exceptions\RestException
     */
    public function encode()
    {
        /**
         * Set the headers as 'application/json' by default.
         */
        $this->headers['Content-Type'] = 'application/json';

        /**
         * Perform the main encode and check if
         * there are any errors.
         */
        $this->body = json_encode($this->body);

        /**
         * Check errors
         */
        $errorCode = json_last_error();
        $errorMessage = json_last_error_msg();
        if ($errorCode !== 0) {
            throw new \OtherCode\Rest\Exceptions\DecodeException($errorMessage, $errorCode);
        }
    }
}