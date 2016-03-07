<?php
/**
 * Created by PhpStorm.
 * User: neo
 * Date: 2016/3/5
 * Time: 22:45
 */

namespace aneo\cache;


use InvalidArgumentException;

/**
 * save data to file
 *
 * Class CacheByFile
 * @package aneo\cache
 */
class CacheByFile extends Cache
{

    private $directory;
    private $umask;

    function __construct($directory, $umask = 0002)
    {
        $this->directory = $directory;
        $this->umask = $umask;

        if (!$this->createPathIfNeeded($directory)) {
            throw new InvalidArgumentException(sprintf(
                'The directory "%s" does not exist and could not be created.',
                $directory
            ));
        }

        if (!is_writable($directory)) {
            throw new InvalidArgumentException(sprintf(
                'The directory "%s" is not writable.',
                $directory
            ));
        }
        $this->directory = realpath($directory);
    }


    protected function exists($id)
    {
        return file_exists($this->getFileName($id));
    }

    protected function getLastModified($id)
    {
        return filemtime($this->getFileName($id));
    }

    protected function save($id, $data)
    {
        $filename = $this->getFileName($id);
        $filepath = pathinfo($filename, PATHINFO_DIRNAME);

        if (!$this->createPathIfNeeded($filepath)) {
            return false;
        }

        if (!is_writable($filepath)) {
            throw new InvalidArgumentException(sprintf(
                'The directory "%s" is not writable.',
                $filepath
            ));
        }

        $tmpFile = tempnam($filepath, 'swap');
        @chmod($tmpFile, 0666 & (~$this->umask));

        if (file_put_contents($tmpFile, $data) !== false) {
            if (@rename($tmpFile, $filename)) {
                return true;
            }
            @unlink($tmpFile);
        }

        return false;
    }

    protected function load($id)
    {
        return file_get_contents($this->getFileName($id));
    }

    public function getFileName($id)
    {
        return $this->directory . DIRECTORY_SEPARATOR . $id;
    }

    /**
     * Create path if needed.
     *
     * @param string $path
     * @return bool TRUE on success or if path already exists, FALSE if path cannot be created.
     */
    private function createPathIfNeeded($path)
    {
        if (!is_dir($path)) {
            if (false === @mkdir($path, 0777 & (~$this->umask), true) && !is_dir($path)) {
                return false;
            }
        }
        return true;
    }
} 