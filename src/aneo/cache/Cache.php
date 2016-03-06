<?php
/**
 * Created by PhpStorm.
 * User: neo
 * Date: 2016/3/5
 * Time: 22:22
 */

namespace aneo\cache;


abstract class Cache
{
    private $datas = [];

    /**
     * @param $name
     * @param CacheDataProvider $dataProvider
     * @return mixed
     */
    public function get($name, CacheDataProvider $dataProvider)
    {
        $id = $dataProvider->cacheId($name);
        if (isset($this->datas[$name])) {
            $data=$this->datas[$name];
        } else {
            if (!$this->exists($id) || $dataProvider->isModifiedSince($name, $this->getLastModified($id))) {
                $data = $dataProvider->get($name);
                $this->save($id, $data);
            } else {
                $data = $this->load($id);
            }
            $this->datas[$name] = $data;
        }
        return $data;
    }

    abstract protected function exists($id);

    abstract protected function getLastModified($id);

    abstract protected function save($id, $data);

    abstract protected function load($id);
} 