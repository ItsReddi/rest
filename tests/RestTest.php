<?php

class RestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return \OtherCode\Rest\Rest
     */
    public function testInstantiationAndConfiguration()
    {
        $api = new \OtherCode\Rest\Rest();

        $this->assertInstanceOf('\OtherCode\Rest\Rest', $api);
        $this->assertInstanceOf('\OtherCode\Rest\Core\Configuration', $api->configuration);

        $api->configuration->url = "http://jsonplaceholder.typicode.com/";
        $api->configuration->timeout = 10;

        $this->assertInternalType('array', $api->configuration->toArray());

        /**
         * There are 3 options configured by default, so if we configure
         * two we have a total of 5
         */
        $this->assertCount(5, $api->configuration->toArray());

        return $api;
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     */
    public function testDirectConfiguration(\OtherCode\Rest\Rest $api)
    {
        $api->addHeader('some_header', "some_value");
        $this->assertCount(1, $api->configuration->httpheader);

        $api->addHeaders(array(
            'some_header_1' => 'some_value_1',
            'some_header_2' => 'some_value_2',
        ));
        $this->assertCount(3, $api->configuration->httpheader);

        $api->removeHeader('some_header');
        $this->assertCount(2, $api->configuration->httpheader);

        $api->removeHeaders(array(
            'some_header_1',
            'some_header_2',
        ));
        $this->assertCount(0, $api->configuration->httpheader);
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     * @return \OtherCode\Rest\Rest
     */
    public function testGetMethod(\OtherCode\Rest\Rest $api)
    {
        $response = $api->get("posts/1");

        $this->assertInstanceOf('OtherCode\Rest\Payloads\Response', $response);
        $this->assertInstanceOf('OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertInstanceOf('OtherCode\Rest\Core\Error', $response->error);

        $this->assertInternalType('array', $response->metadata);
        $this->assertInternalType('int', $response->code);
        $this->assertInternalType('string', $response->content_type);
        $this->assertInternalType('string', $response->charset);

        return $api;
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     */
    public function testPostMethod(\OtherCode\Rest\Rest $api)
    {
        $response = $api->post("posts", json_encode(array(
            'title' => 'foo',
            'body' => 'bar',
            'userId' => 1
        )));

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Response', $response);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertInstanceOf('\OtherCode\Rest\Core\Error', $response->error);

        $this->assertInternalType('array', $response->metadata);
        $this->assertInternalType('int', $response->code);
        $this->assertInternalType('string', $response->content_type);
        $this->assertInternalType('string', $response->charset);
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     */
    public function testPutMethod(\OtherCode\Rest\Rest $api)
    {
        $response = $api->put("posts/1", json_encode(array(
            'id' => 1,
            'title' => 'foo',
            'body' => 'bar',
            'userId' => 1
        )));

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Response', $response);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertInstanceOf('\OtherCode\Rest\Core\Error', $response->error);

        $this->assertInternalType('array', $response->metadata);
        $this->assertInternalType('int', $response->code);
        $this->assertInternalType('string', $response->content_type);
        $this->assertInternalType('string', $response->charset);
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     */
    public function testPatchMethod(\OtherCode\Rest\Rest $api)
    {
        $response = $api->patch("posts/1", json_encode(array(
            'body' => 'bar',
        )));

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Response', $response);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertInstanceOf('\OtherCode\Rest\Core\Error', $response->error);

        $this->assertInternalType('array', $response->metadata);
        $this->assertInternalType('int', $response->code);
        $this->assertInternalType('string', $response->content_type);
        $this->assertInternalType('string', $response->charset);
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     */
    public function testDeleteMethod(\OtherCode\Rest\Rest $api)
    {
        $response = $api->delete("posts/1");

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Response', $response);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertInstanceOf('\OtherCode\Rest\Core\Error', $response->error);

        $this->assertInternalType('array', $response->metadata);
        $this->assertInternalType('int', $response->code);
        $this->assertInternalType('string', $response->content_type);
        $this->assertInternalType('string', $response->charset);
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     */
    public function testHeadMethod(\OtherCode\Rest\Rest $api)
    {
        $response = $api->head("posts");

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Response', $response);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertInstanceOf('\OtherCode\Rest\Core\Error', $response->error);

        $this->assertInternalType('array', $response->metadata);
        $this->assertInternalType('int', $response->code);
        $this->assertInternalType('string', $response->content_type);
        $this->assertInternalType('string', $response->charset);

        $this->assertNull($response->body);
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     */
    public function testErrorControl(\OtherCode\Rest\Rest $api)
    {
        $this->assertInstanceOf('\OtherCode\Rest\Core\Error', $api->getError());
        $this->assertInternalType('boolean', $api->hasError());
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testGetMethod
     */
    public function testPayloads(\OtherCode\Rest\Rest $api)
    {
        $payloads = $api->getPayloads();

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Request', $payloads['request']);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Response', $payloads['response']);
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testGetMethod
     */
    public function testMetadata(\OtherCode\Rest\Rest $api)
    {
        $this->assertInternalType('array', $api->getMetadata());
    }

    /**
     * @param \OtherCode\Rest\Rest $api
     * @depends testInstantiationAndConfiguration
     * @expectedException \OtherCode\Rest\Exceptions\ConnectionException
     */
    public function testException(\OtherCode\Rest\Rest $api)
    {
        $api->configuration->url = "http://thisurlnotexists.com/";
        $api->get("posts/1");
    }

    /**
     * @expectedException \OtherCode\Rest\Exceptions\RestException
     */
    public function testCoreCallWrongVerb()
    {
        $core = new \Tests\Rest\CoreTester();
        $core->returnCall('wrong', 'http://jsonplaceholder.typicode.com/posts/1');
    }

    public function testGetRawCoreCall()
    {
        $core = new \Tests\Rest\CoreTester();
        $response = $core->returnCall('GET', 'http://jsonplaceholder.typicode.com/posts/1', 'param=value');

        $this->assertInstanceOf('OtherCode\Rest\Payloads\Response', $response);
        $this->assertInstanceOf('OtherCode\Rest\Payloads\Headers', $response->headers);
        $this->assertInstanceOf('OtherCode\Rest\Core\Error', $response->error);

        $this->assertInternalType('array', $response->metadata);
        $this->assertInternalType('int', $response->code);
        $this->assertInternalType('string', $response->content_type);
        $this->assertInternalType('string', $response->charset);
    }

}