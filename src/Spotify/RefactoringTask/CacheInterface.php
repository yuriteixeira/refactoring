<?php

namespace Spotify\RefactoringTask;

interface CacheInterface
{
    public function __construct(array $options);

    /**
     * Set a value on cache
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);

    /**
     * Get a cached value
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);
}