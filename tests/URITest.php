<?php

class URITest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic instantiation test
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function testInstantiation()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com:9090/path?arg=value#anchor');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $uri);

        return $uri;
    }

    /**
     * Test each component from the URI
     * @depends testInstantiation
     * @param \OtherCode\Rest\Payloads\Uri $uri
     */
    public function testGetComponents(\OtherCode\Rest\Payloads\Uri $uri)
    {
        $this->assertInternalType('string', $uri->getScheme());
        $this->assertEquals('http', $uri->getScheme());

        $this->assertInternalType('string', $uri->getHost());
        $this->assertEquals('hostname.com', $uri->getHost());

        $this->assertInternalType('integer', $uri->getPort());
        $this->assertEquals(9090, $uri->getPort());

        $this->assertInternalType('string', $uri->getPath());
        $this->assertEquals('/path', $uri->getPath());

        $this->assertInternalType('string', $uri->getQuery());
        $this->assertEquals('arg=value', $uri->getQuery());

        $this->assertInternalType('string', $uri->getFragment());
        $this->assertEquals('anchor', $uri->getFragment());
    }

    /**
     * Test all the different casuistic of getUserInfo() method
     */
    public function testUserInformation()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com');
        $this->assertInternalType('string', $uri->getUserInfo());
        $this->assertEquals('username:password', $uri->getUserInfo());

        $uri = new \OtherCode\Rest\Payloads\Uri('http://username@hostname.com');
        $this->assertInternalType('string', $uri->getUserInfo());
        $this->assertEquals('username', $uri->getUserInfo());

        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:@hostname.com');
        $this->assertInternalType('string', $uri->getUserInfo());
        $this->assertEquals('username', $uri->getUserInfo());

        $uri = new \OtherCode\Rest\Payloads\Uri('http://:password@hostname.com');
        $this->assertInternalType('string', $uri->getUserInfo());
        $this->assertEquals('', $uri->getUserInfo());

        $uri = new \OtherCode\Rest\Payloads\Uri('http://hostname.com');
        $this->assertInternalType('string', $uri->getUserInfo());
        $this->assertEquals('', $uri->getUserInfo());
    }

    /**
     * Test the URI Authority
     */
    public function testGetAuthority()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com');
        $this->assertInternalType('string', $uri->getAuthority());
        $this->assertEquals('username:password@hostname.com', $uri->getAuthority());

        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com:80');
        $this->assertInternalType('string', $uri->getAuthority());
        $this->assertEquals('username:password@hostname.com', $uri->getAuthority());

        $uri = new \OtherCode\Rest\Payloads\Uri('http://username@hostname.com');
        $this->assertInternalType('string', $uri->getAuthority());
        $this->assertEquals('username@hostname.com', $uri->getAuthority());

        $uri = new \OtherCode\Rest\Payloads\Uri('http://hostname.com:9090');
        $this->assertInternalType('string', $uri->getAuthority());
        $this->assertEquals('hostname.com:9090', $uri->getAuthority());
    }

    /**
     * Test the withScheme() methods.
     */
    public function testWithScheme()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com');

        $withScheme = $uri->withScheme('https');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withScheme);
        $this->assertEquals('https', $withScheme->getScheme());

        $withScheme = $uri->withScheme('ftp');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withScheme);
        $this->assertEquals('ftp', $withScheme->getScheme());

        $withScheme = $uri->withScheme('');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withScheme);
        $this->assertEquals('', $withScheme->getScheme());

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $uri);
        $this->assertEquals('http', $uri->getScheme());
    }

    /**
     * Test the scheme validations
     * @expectedException \InvalidArgumentException
     */
    public function testWithSchemeException()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com');
        $uri->withScheme('thisSchemeIsInvalid!');
    }


}
