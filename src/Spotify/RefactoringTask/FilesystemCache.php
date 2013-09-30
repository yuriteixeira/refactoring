<?php

namespace Spotify\RefactoringTask;

class FilesystemCache implements CacheInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Set a value on cache
     *
     * @param string $key
     * @param mixed $value
     * @throws CacheException
     */
    public function set($key, $value)
    {
        if (!isset($this->options['cache_dir'])) {
            throw new CacheException('Missing option - cache_dir.');
        }

        $cacheDir = $this->options['cache_dir'];

        if (!file_exists($cacheDir) && !@mkdir($cacheDir, 0777, true)) {
            throw new CacheException('Failed to create cache directory.');
        }

        $cachePath = "{$cacheDir}/{$key}";

        if (!@file_put_contents($cachePath, serialize($value))) {
            throw new CacheException('Failed to write cache content.');
        }
    }

    /**
     * Get a cached value
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $cacheDir = $this->options['cache_dir'];
        $cachePath = "{$cacheDir}/{$key}";
        $serializedCachedContent = @file_get_contents($cachePath);
        $cachedContent = unserialize($serializedCachedContent);

        return $cachedContent;
    }
}