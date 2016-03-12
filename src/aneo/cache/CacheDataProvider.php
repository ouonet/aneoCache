<?php
/**
 * Created by PhpStorm.
 * User: neo
 * Date: 2016/3/5
 * Time: 22:35
 */

namespace aneo\cache;

/**
 * Example
 * <code>
 * use aneo\cache\CacheDataProvider;
 *
 * Class HtmlTemplate implements  CacheDataProvider{ *
 *   function get($name) {
 *      return $this->compile($name);
 *   }
 *
 *   function isModifiedSince($name, $time) {
 *     return filemtime($name)>=$time; *
 *   }
 *
 *   function cacheId($name) {
 *     return 'htmlTemplate\\'.$name.'.php';
 *   }
 *   function compile($name){
 *     //.............
 *     return $html;
 *   }
 * }
 * </code>
 *
 * Interface CacheDataProvider
 * @package aneo\cache
 */
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

    /**
     * optional
     * @param $data
     * @return mixed
     */
//    function decode($data);

    /**
     * optional
     * @param $data
     * @return mixed
     */
//    function encode($data);

    /**
     * optional
     * @param $data
     * @return mixed
     */
//    function initial($data);
}
