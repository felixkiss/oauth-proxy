<?php namespace Felixkiss\Proxy\Config;

class Cookie
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $ttl;

    /**
     * @param string $name
     */
    public function __construct($name, $ttl)
    {
        $this->name = $name;
        $this->ttl = $ttl;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }
}
