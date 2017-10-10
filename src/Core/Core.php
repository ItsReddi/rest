<?php

namespace OtherCode\Rest\Core;

/**
 * Class Core
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.3.1
 * @package OtherCode\Rest\Core
 */
abstract class Core
{

    /**
     * Core version
     */
    const VERSION = "1.4.0";

    /**
     * Configuration class
     * @var \OtherCode\Rest\Core\Configuration
     */
    public $configuration;

    /**
     * The data to be send
     * @var \OtherCode\Rest\Payloads\Request
     */
    protected $request;

    /**
     * Stack with the response data
     * @var \OtherCode\Rest\Payloads\Response
     */
    protected $response;

    /**
     * List of loaded modules
     * @var array
     */
    protected $modules = array(
        'before' => array(),
        'after' => array(),
    );

    /**
     * Main constructor
     * @param Configuration $configuration
     */
    public function __construct(\OtherCode\Rest\Core\Configuration $configuration = null)
    {
        $this->response = new \OtherCode\Rest\Payloads\Response();
        $this->request = new \OtherCode\Rest\Payloads\Request();

        $this->configure($configuration);
    }

    /**
     * Configure main options
     * @param Configuration $configuration
     * @throws \OtherCode\Rest\Exceptions\RestException
     * @return $this
     */
    public function configure(\OtherCode\Rest\Core\Configuration $configuration = null)
    {
        if (isset($configuration)) {
            $this->configuration = $configuration;
        } else {
            $this->configuration = new \OtherCode\Rest\Core\Configuration();
        }
        $this->request->setHeaders($this->configuration->httpheader);
        return $this;
    }

    /**
     * Method: POST, PUT, GET etc
     * @param string $method
     * @param string $url
     * @param mixed $body
     * @throws \OtherCode\Rest\Exceptions\RestException
     * @throws \OtherCode\Rest\Exceptions\ConfigurationException
     * @throws \OtherCode\Rest\Exceptions\ConnectionException
     * @return \OtherCode\Rest\Payloads\Response
     */
    protected function call($method, $url, $body = null)
    {
        $curl = curl_init();

        /**
         * @TODO merge the request object with the global configuration object
         */
        $this->request->setBody(new \OtherCode\Rest\Payloads\Stream($body));
        $this->request->method = strtoupper($method);
        $this->request->uri = $this->configuration->url . $url;

        /**
         * We configure the domain and url where we will
         * make the request ir exists.
         */
        curl_setopt($curl, CURLOPT_URL, $this->request->uri);

        /**
         * In case we have some modules attached to
         * "before" hook, we run them here
         */
        $this->dispatchModules('before');

        /**
         * Switch between the different configurations
         * depending the method used
         * @todo apply this as "default conf"
         */
        switch ($this->request->method) {
            case "HEAD":
                curl_setopt($curl, CURLOPT_NOBODY, true);
                break;
            case "GET":
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->request->method);

                break;
            case "POST":
            case "PUT":
            case "PATCH":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->request->method);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->request->body);
                break;
            default:
                throw new \OtherCode\Rest\Exceptions\RestException('Method "' . $this->request->method . '" not supported!');
        }

        if (!curl_setopt_array($curl, $this->configuration->toArray())) {
            throw new \OtherCode\Rest\Exceptions\ConfigurationException("It has not been possible to configure the instance, check your configuration options");
        }

        /**
         * Main execution
         */
        $response = curl_exec($curl);

        /**
         * Check errors
         */
        if (curl_errno($curl) !== 0) {
            throw new \OtherCode\Rest\Exceptions\ConnectionException(curl_error($curl), curl_errno($curl));
        }

        /**
         * @todo create the input stream
         */
        $this->response->parseResponse($response);
        $this->response->setMetadata(curl_getinfo($curl));

        /**
         * In case we have some modules attached to
         * "after" hook, we run them here
         */
        $this->dispatchModules('after');

        /**
         * Close the current connection
         */
        curl_close($curl);

        /**
         * Return the final response processed or not
         * by the modules
         */
        return $this->response;
    }

    /**
     * Get the curl request headers
     * @return array
     */
    public function getMetadata()
    {
        return $this->response->metadata;
    }

    /**
     * Run all the registered modules
     * @param string $hook Hook module name
     * @throws \OtherCode\Rest\Exceptions\RestException
     */
    private function dispatchModules($hook)
    {
        foreach ($this->modules[$hook] as $module) {
            if (method_exists($module, 'run')) {
                $module->run();
            }
        }
    }

    /**
     * Register a new module instance
     * @param string $moduleName
     * @param \OtherCode\Rest\Modules\BaseModule $moduleInstance
     * @param string $hook
     * @return boolean
     */
    protected function registerModule($moduleName, \OtherCode\Rest\Modules\BaseModule $moduleInstance, $hook)
    {
        if (!in_array($hook, array_keys($this->modules))) {
            return false;
        }
        if (!array_key_exists($moduleName, $this->modules[$hook])) {
            $this->modules[$hook][$moduleName] = $moduleInstance;
            return true;
        }
        return null;
    }

    /**
     * Unregister the module specified by $moduleName
     * @param $moduleName string
     * @param string $hook
     * @return boolean
     */
    protected function unregisterModule($moduleName, $hook)
    {
        if (!in_array($hook, array_keys($this->modules))) {
            return false;
        }
        if (array_key_exists($moduleName, $this->modules[$hook])) {
            unset($this->modules[$hook][$moduleName]);
            return true;
        }
        return null;
    }

}