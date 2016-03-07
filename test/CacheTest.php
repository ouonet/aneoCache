<?php
use aneo\cache\CacheByFile;

/**
 * Created by PhpStorm.
 * User: neo
 * Date: 2016/3/7
 * Time: 14:53
 */

class CacheTest extends PHPUnit_Framework_TestCase {

    public function testContructor()
    {
        $cache=new CacheByFile(__DIR__);
        var_dump($cache);
    }
}
 