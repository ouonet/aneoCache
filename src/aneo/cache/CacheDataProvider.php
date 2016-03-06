<?php
/**
 * Created by PhpStorm.
 * User: neo
 * Date: 2016/3/5
 * Time: 22:35
 */

namespace aneo\cache;


interface CacheDataProvider
{
    /**
     *
     * @param string $name
     * @return string , should return serialized string of data.eg. json_encode($sth) or serialized($sth)
     *    strong recommend use json_encode, cause of efficiency,about 5 times
     */
    function get($name);

    /**
     * @param string $name
     * @param int $time
     * @return bool
     */
    function isModifiedSince($name, $time);

    /**
     * @param string $name
     * @return string
     */
    function cacheId($name);
}