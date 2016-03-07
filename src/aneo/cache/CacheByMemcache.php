<?php
/**
 * Created by PhpStorm.
 * User: neo
 * Date: 2016/3/6
 * Time: 1:42
 */

namespace aneo\cache;


use Memcache;

/**
 * save data to memecache
 *
 * Class CacheByMemcache
 * @package aneo\cache
 */
class CacheByMemcache extends Cache
{
    /**
     * @var Memcache
     */
    private $memcache = null;
    const TIME_SUFFIX = "time";

    function __construct($memcache)
    {
        $this->memcache = $memcache;
    }


    protected function  exists($id)
    {
        return $this->memcache->get($id . self::TIME_SUFFIX) != false;
    }

    protected function getLastModified($id)
    {
        $t = $this->memcache->get($id . self::TIME_SUFFIX);
        return $t;
    }

    protected function save($id, $data)
    {
        $this->memcache->set($id, $data);
        $this->memcache->set($id . self::TIME_SUFFIX, microtime(true));
    }

    protected function load($id)
    {
        return $this->memcache->get($id);
    }
}