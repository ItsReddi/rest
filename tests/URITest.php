<?php

class URITest extends \PHPUnit\Framework\TestCase
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
     * Test the withScheme() method
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

    /**
     * Test withUserInfo() method.
     */
    public function testWithUserInfo()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com');

        $withUserInfo = $uri->withUserInfo('otheruser', 'otherpassword');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withUserInfo);
        $this->assertEquals('otheruser:otherpassword', $withUserInfo->getUserInfo());

        $withUserInfo = $uri->withUserInfo('otheruser');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withUserInfo);
        $this->assertEquals('otheruser', $withUserInfo->getUserInfo());

        $withUserInfo = $uri->withUserInfo('');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withUserInfo);
        $this->assertEquals('', $withUserInfo->getUserInfo());

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $uri);
        $this->assertEquals('username:password', $uri->getUserInfo());
    }

    /**
     * Test withHost() method
     */
    public function testWithHost()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com');

        $withHost = $uri->withHost('otherhost.net');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withHost);
        $this->assertEquals('otherhost.net', $withHost->getHost());

        $withHost = $uri->withHost('');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withHost);
        $this->assertEquals('', $withHost->getHost());

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $uri);
        $this->assertEquals('hostname.com', $uri->getHost());
    }

    /**
     * Test host validation
     * @expectedException \InvalidArgumentException
     */
    public function testWithHostException()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com');
        $uri->withHost('this host is (not) valid!!');
    }

    /**
     * Test withPort() methods
     */
    public function testWithPort()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://hostname.com:8080');

        $withPort = $uri->withPort(9000);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withPort);
        $this->assertEquals(9000, $withPort->getPort());

        $withPort = $uri->withPort(80);
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withPort);
        $this->assertEquals(80, $withPort->getPort());

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $uri);
        $this->assertEquals(8080, $uri->getPort());

    }

    /**
     * Test port exception
     * @expectedException \InvalidArgumentException
     */
    public function testWithPortExceptionWrongType()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://hostname.com');
        $uri->withPort('8080');
    }

    /**
     * Test port exception
     * @expectedException \InvalidArgumentException
     */
    public function testWithPortExceptionWrongRangeMin()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://hostname.com');
        $uri->withPort(-1);
    }

    /**
     * Test port exception
     * @expectedException \InvalidArgumentException
     */
    public function testWithPortExceptionWrongRangeMax()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://hostname.com');
        $uri->withPort(65536);
    }

    /**
     * Test withFragment() method
     */
    public function testWithQuery()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com:9090/path?arg=value#anchor');

        $withQuery = $uri->withQuery('key=param');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withQuery);
        $this->assertEquals('key=param', $withQuery->getQuery());

        $withQuery = $uri->withQuery('other=thing&key=param');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withQuery);
        $this->assertEquals('other=thing&key=param', $withQuery->getQuery());

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $uri);
        $this->assertEquals('arg=value', $uri->getQuery());
    }

    /**
     * Test query exception
     * @expectedException \InvalidArgumentException
     */
    public function testWithQueryException()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com:9090/path?arg=value#anchor');
        $uri->withQuery('one=one1two=two2');
    }

    /**
     * Test withFragment() method
     */
    public function testWithFragment()
    {
        $uri = new \OtherCode\Rest\Payloads\Uri('http://username:password@hostname.com:9090/path?arg=value#anchor');

        $withFragment = $uri->withFragment('some%20fragment');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withFragment);
        $this->assertEquals('some%20fragment', $withFragment->getFragment());

        $withFragment = $uri->withFragment('other fragment');
        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $withFragment);
        $this->assertEquals('other%20fragment', $withFragment->getFragment());

        $this->assertInstanceOf('\OtherCode\Rest\Payloads\Uri', $uri);
        $this->assertEquals('anchor', $uri->getFragment());
    }

    /**
     * Test toString() method
     */
    public function testToString()
    {
        $variants = array(
            'http://username:password@hostname.com:9090/path?arg=value#anchor',
            'http://username:password@hostname.com:9090/path?arg=value',
            'http://username:password@hostname.com:9090/path',
            'http://username:password@hostname.com:9090',
            'http://username:password@hostname.com',
            'http://username@hostname.com',
            'http://hostname.com',
            'hostname.com',
        );

        foreach ($variants as $variant) {
            $uri = new \OtherCode\Rest\Payloads\Uri($variant);
            $this->assertInternalType('string', (string)$uri);
            $this->assertEquals($variant, (string)$uri);
        }
    }
}
