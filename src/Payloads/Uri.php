<?php

namespace OtherCode\Rest\Payloads;


/**
 * Uri
 * @author SofCloudIT <info@sofcloudit.com>
 * @author Unay Santisteban <davidu@softcom.com>
 * @copyright Copyright (C) 2017 Ingram Micro Inc. Any rights not granted herein
 * are reserved for Ingram Micro Inc. Permission to use, copy and distribute this
 * source code without fee and without a signed license agreement is hereby granted
 * provided that: (i) the above copyright notice and this paragraph appear in all
 * copies and distributions; and (ii) the source code is only used, copied or
 * distributed for the purpose of using it with the APS package for which Ingram Micro Inc.
 * or its affiliates integrated it into.  Ingram Micro Inc. may revoke the limited license
 * granted herein at any time at its sole discretion. THIS SOURCE CODE IS PROVIDED
 * "AS IS". INGRAM MICRO INC. MAKES NO REPRESENTATIONS OR WARRANTIES AND DISCLAIMS
 * ALL IMPLIED WARRANTIES OF MERCHANTABILITY OR FITNESS FOR ANY PARTICULAR PURPOSE.
 * @package OtherCode\Rest\Payloads
 */
class Uri implements \Psr\Http\Message\UriInterface
{

    /**
     * Scheme component
     * @var string
     */
    public $scheme;

    /**
     * Host component
     * @var string
     */
    public $host;

    /**
     * Port component
     * @var string
     */
    public $port;

    /**
     * User component
     * @var string
     */
    public $user;

    /**
     * Password component
     * @var string
     */
    public $pass;

    /**
     * Path component
     * @var string
     */
    public $path;

    /**
     * String after the question mark ?
     * @var string
     */
    public $query;

    /**
     * String after the hashmark #
     * @var string
     */
    public $fragment;

    /**
     * Uri constructor.
     * @param string $uri
     */
    public function __construct($uri)
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

    public function getAuthority()
    {
    }

    /**
     *
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
        return $this->path;
    }

    /**
     * Retrieve the query string of the URI.
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }


    public function withScheme($scheme)
    {
    }

    public function withUserInfo($user, $password = null)
    {
    }

    public function withHost($host)
    {
    }

    public function withPort($port)
    {
    }

    public function withPath($path)
    {
    }

    public function withQuery($query)
    {
    }

    public function withFragment($fragment)
    {
    }

    public function __toString()
    {
    }

}