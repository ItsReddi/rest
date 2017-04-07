<?php

namespace OtherCode\Rest\Payloads;


/**
 * Class URI
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @version 1.0
 * @package OtherCode\Rest\Payloads
 */
class Uri implements \Psr\Http\Message\UriInterface
{

    /**
     * Scheme component
     * @var string
     */
    protected $scheme;

    /**
     * Host component
     * @var string
     */
    protected $host;

    /**
     * Port component
     * @var string
     */
    protected $port;

    /**
     * User component
     * @var string
     */
    protected $user;

    /**
     * Password component
     * @var string
     */
    protected $pass;

    /**
     * Path component
     * @var string
     */
    protected $path;

    /**
     * String after the question mark ?
     * @var string
     */
    protected $query;

    /**
     * String after the hashmark #
     * @var string
     */
    protected $fragment;

    /**
     * Valid list of uri schemes
     * @see https://www.iana.org/assignments/uri-schemes/uri-schemes.xml
     * @var array
     */
    private $validSchemes = array(
        '', 'aaa', 'aaas', 'about', 'acap', 'acct', 'acr', 'adiumxtra', 'afp', 'afs', 'aim', 'appdata', 'apt', 'attachment', 'aw',
        'barion', 'beshare', 'bitcoin', 'blob', 'bolo', 'browserext', 'callto', 'cap', 'chrome', 'chrome-extension', 'cid', 'coap',
        'coaps', 'com-eventbrite-attendee', 'content', 'crid', 'cvs', 'data', 'dav', 'dict', 'dis', 'dlna-playcontainer',
        'dlna-playsingle', 'dns', 'dntp', 'dtn', 'dvb', 'ed2k', 'example', 'facetime', 'fax', 'feed', 'feedready', 'file',
        'filesystem', 'finger', 'fish', 'ftp', 'geo', 'gg', 'git', 'gizmoproject', 'go', 'gopher', 'gtalk', 'h323', 'ham',
        'hcp', 'http', 'https', 'hydrazone', 'iax', 'icap', 'icon', 'im', 'imap', 'info', 'iotdisco', 'ipn', 'ipp', 'ipps',
        'irc', 'irc6', 'ircs', 'iris', 'iris.beep', 'iris.lwz', 'iris.xpc', 'iris.xpcs', 'isostore', 'itms', 'jabber', 'jar',
        'jms', 'keyparc', 'lastfm', 'ldap', 'ldaps', 'lvlt', 'magnet', 'mailserver', 'mailto', 'maps', 'market', 'message',
        'mid', 'mms', 'modem', 'moz', 'ms-access', 'ms-browser-extension', 'ms-drive-to', 'ms-enrollment', 'ms-excel',
        'ms-gamebarservices', 'ms-getoffice', 'ms-help', 'ms-infopath', 'ms-media-stream-id', 'ms-officeapp', 'ms-project',
        'ms-powerpoint', 'ms-publisher', 'ms-search-repair', 'ms-secondary-screen-controller', 'ms-secondary-screen-setup',
        'ms-settings', 'ms-settings-airplanemode', 'ms-settings-bluetooth', 'ms-settings-camera', 'ms-settings-cellular',
        'ms-settings-cloudstorage', 'ms-settings-connectabledevices', 'ms-settings-displays-topology', 'ms-settings-emailandaccounts',
        'ms-settings-language', 'ms-settings-location', 'ms-settings-lock', 'ms-settings-nfctransactions', 'ms-settings-notifications',
        'ms-settings-power', 'ms-settings-privacy', 'ms-settings-proximity', 'ms-settings-screenrotation', 'ms-settings-wifi',
        'ms-settings-workplace', 'ms-spd', 'ms-sttoverlay', 'ms-transit-to', 'ms-virtualtouchpad', 'ms-visio', 'ms-walk-to', 'ms-word',
        'msnim', 'msrp', 'msrps', 'mtqp', 'mumble', 'mupdate', 'mvn', 'news', 'nfs', 'ni', 'nih', 'nntp', 'notes', 'ocf', 'oid',
        'opaquelocktoken', 'pack', 'palm', 'paparazzi', 'pkcs11', 'platform', 'pop', 'pres', 'prospero', 'proxy', 'pwid', 'psyc',
        'qb', 'query', 'redis', 'rediss', 'reload', 'res', 'resource', 'rmi', 'rsync', 'rtmfp', 'rtmp', 'rtsp', 'rtsps', 'rtspu',
        'secondlife', 'service', 'session', 'sftp', 'sgn', 'shttp', 'sieve', 'sip', 'sips', 'skype', 'smb', 'sms', 'smtp', 'snews',
        'snmp', 'soap.beep', 'soap.beeps', 'soldat', 'spotify', 'ssh', 'steam', 'stun', 'stuns', 'submit', 'svn', 'tag', 'teamspeak',
        'tel', 'teliaeid', 'telnet', 'tftp', 'things', 'thismessage', 'tip', 'tn3270', 'tool', 'turn', 'turns', 'tv', 'udp', 'unreal',
        'urn', 'ut2004', 'v-event', 'vemmi', 'ventrilo', 'videotex', 'vnc', 'view-source', 'wais', 'webcal', 'wpid', 'ws', 'wss', 'wtai',
        'wyciwyg', 'xcon', 'xcon-userid', 'xfire', 'xmlrpc.beep', 'xmlrpc.beeps', 'xmpp', 'xri', 'ymsgr', 'z39.50', 'z39.50r', 'z39.50s'
    );

    /**
     * Uri constructor.
     * @param string $uri
     */
    public function __construct($uri = null)
    {
        if (!is_string($uri)) {
            throw new \InvalidArgumentException('The URI parameter must be a string, a ' . gettype($uri) . ' is given.');
        }

        foreach (parse_url($uri) as $component => $value) {
            $this->{$component} = $value;

        }
    }

    /**
     * Retrieve the scheme component of the URI.
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Retrieve the authority component of the URI
     * @return string
     */
    public function getAuthority()
    {
        $authority = '';

        $buffer = $this->getUserInfo();
        if (!empty($buffer)) {
            $authority .= $this->getUserInfo() . '@';
        }

        $buffer = $this->getHost();
        if (!empty($buffer)) {
            $authority .= $this->getHost();
        }

        $buffer = $this->getPort();
        if (!empty($buffer) && $buffer !== 80) {
            $authority .= ':' . $this->getPort();
        }
        return $authority;
    }

    /**
     * Retrieve the user information component of the URI.
     * @return string
     */
    public function getUserInfo()
    {
        $userInformation = '';
        if (!empty($this->user)) {
            $userInformation .= $this->user;
            if (!empty($this->pass)) {
                $userInformation .= ':' . $this->pass;
            }
        }

        return $userInformation;
    }

    /**
     * Retrieve the host component of the URI.
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Retrieve the port component of the URI.
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Retrieve the path component of the URI.
     * @return string
     */
    public function getPath()
    {
        return rawurldecode($this->path);
    }

    /**
     * Retrieve the query string of the URI.
     * @return string
     */
    public function getQuery()
    {
        return rawurldecode($this->query);
    }

    /**
     * Retrieve the fragment component of the URI.
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Return an instance with the specified scheme.
     * @param string $scheme
     * @throws \InvalidArgumentException
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function withScheme($scheme)
    {
        if (!is_string($scheme)) {
            throw new \InvalidArgumentException('Scheme must be a string, given ' . gettype($scheme));
        }

        $scheme = strtolower($scheme);

        if (!in_array($scheme, $this->validSchemes)) {
            throw new \InvalidArgumentException($scheme . " is not a valid URI Scheme.");
        }

        $uri = clone $this;
        $uri->scheme = $scheme;

        return $uri;
    }

    /**
     * @param string $user
     * @param string $password
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function withUserInfo($user, $password = null)
    {
        if (!is_string($user)) {
            throw new \InvalidArgumentException('User must be a string, given a ' . gettype($user));
        }

        if (isset($password) && !is_string($password)) {
            throw new \InvalidArgumentException('User must be a string, given a ' . gettype($user));
        }

        $uri = clone $this;
        $uri->user = $user;
        $uri->pass = $password;

        return $uri;
    }

    /**
     * Return an instance with the specified host.
     * @param string $host
     * @throws \InvalidArgumentException
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function withHost($host)
    {
        if (!is_string($host)) {
            throw new \InvalidArgumentException('Host must be a string, given ' . gettype($host));
        }

        $host = strtolower($host);

        if (!empty($host) && preg_match('/^([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $host) !== 1) {
            throw new \InvalidArgumentException('Invalid host name.');
        }

        $uri = clone $this;
        $uri->host = $host;

        return $uri;
    }

    /**
     * Return an instance with the specified port.
     * @param int|null $port
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function withPort($port)
    {
        if (!is_int($port) || $port > 65535 || $port < 0) {
            throw new \InvalidArgumentException('Invalid port, must be an integer between 1 and 65535.');
        }

        $uri = clone $this;
        $uri->port = $port;

        return $uri;
    }

    /**
     * Return an instance with the specified path.
     * @param string $path
     * @throws \InvalidArgumentException
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function withPath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException('Path must be a string, given ' . gettype($path));
        }

        $path = strtolower($path);

        $uri = clone $this;
        $uri->path = rawurlencode(rawurldecode($path));

        return $uri;
    }

    /**
     * @param string $query
     * @throws \InvalidArgumentException
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function withQuery($query)
    {
        if (!is_string($query)) {
            throw new \InvalidArgumentException('Query must be a string, given ' . gettype($query));
        }

        $query = strtolower($query);

        if (strpos($query, '=') === false) {
            throw new \InvalidArgumentException('Invalid query string it must be key1=param1 format.');
        }

        if (substr_count($query, '=') > 1 && strpos($query, '&') === false) {
            throw new \InvalidArgumentException('Invalid query string, multiple parameters must been separated by "&"');
        }

        $uri = clone $this;
        $uri->query = rawurlencode(rawurldecode(strtolower($query)));

        return $uri;
    }

    /**
     * @param string $fragment
     * @throws \InvalidArgumentException
     * @return \OtherCode\Rest\Payloads\Uri
     */
    public function withFragment($fragment)
    {
        if (!is_string($fragment)) {
            throw new \InvalidArgumentException('Fragment must be a string, given ' . gettype($fragment));
        }

        $fragment = strtolower($fragment);

        $uri = clone $this;
        $uri->fragment = rawurlencode(rawurldecode($fragment));

        return $uri;
    }

    /**
     * Return the URI as string format
     * @return string
     */
    public function __toString()
    {
        $uri = '';

        $buffer = $this->getScheme();
        if (!empty($buffer)) {
            $uri .= $buffer . '://';
        }

        $buffer = $this->getAuthority();
        if (!empty($buffer)) {
            $uri .= $buffer;
        }

        $buffer = $this->getPath();
        if (!empty($buffer)) {
            $uri .= $buffer;
        }

        $buffer = $this->getQuery();
        if (!empty($buffer)) {
            $uri .= '?' . $buffer;
        }

        $buffer = $this->getFragment();
        if (!empty($buffer)) {
            $uri .= '#' . $buffer;
        }

        return $uri;

    }

}