<?php
/*************************************************************************************
 *   MIT License                                                                     *
 *                                                                                   *
 *   Copyright (C) 2009-2019 by Nurlan Mukhanov <nurike@gmail.com>                   *
 *                                                                                   *
 *   Permission is hereby granted, free of charge, to any person obtaining a copy    *
 *   of this software and associated documentation files (the "Software"), to deal   *
 *   in the Software without restriction, including without limitation the rights    *
 *   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell       *
 *   copies of the Software, and to permit persons to whom the Software is           *
 *   furnished to do so, subject to the following conditions:                        *
 *                                                                                   *
 *   The above copyright notice and this permission notice shall be included in all  *
 *   copies or substantial portions of the Software.                                 *
 *                                                                                   *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR      *
 *   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,        *
 *   FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE    *
 *   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER          *
 *   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,   *
 *   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE   *
 *   SOFTWARE.                                                                       *
 ************************************************************************************/

namespace DBD\Base;

use DBD\Cache;
use Exception;
use Psr\SimpleCache\CacheInterface;

final class Config
{
    /** @var string $dsn */
    private $dsn;
    /** @var int $port */
    private $port;
    /** @var string $database */
    private $database;
    /** @var string $username */
    private $username;
    /** @var string $password */
    private $password;
    /** @var string $identity Connection Name */
    private $identity = "DBD-PHP";
    /** @var CacheInterface|Cache $cacheDriver */
    private $cacheDriver = null;

    /**
     * Config constructor.
     * @param $dsn
     * @param $port
     * @param $database
     * @param $username
     * @param $password
     * @param null $identity
     */
    public function __construct($dsn, $port, $database, $username, $password, $identity = null)
    {
        $this->dsn = $dsn;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->identity = isset($identity) ? $identity : $this->identity;
    }

    /**
     * @return Cache|CacheInterface
     */
    public function getCacheDriver()
    {
        return $this->cacheDriver;
    }

    /**
     * @param Cache|CacheInterface $cacheDriver
     *
     * @return Config
     * @throws Exception
     */
    public function setCacheDriver($cacheDriver)
    {
        if ($cacheDriver instanceof Cache || $cacheDriver instanceof CacheInterface) {
            $this->cacheDriver = $cacheDriver;

            return $this;
        }

        throw new Exception("Unsupported caching interface. Extend DBD\\Cache or use PSR-16 Common Interface for Caching");
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $database
     *
     * @return Config
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * @param string $dsn
     *
     * @return Config
     */
    public function setDsn($dsn)
    {
        $this->dsn = $dsn;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     *
     * @return Config
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return Config
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return Config
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return Config
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}
