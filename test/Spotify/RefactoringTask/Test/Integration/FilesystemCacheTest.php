<?php

namespace Spotify\RefactoringTask\Test\Integration;

use Spotify\RefactoringTask\FilesystemCache;

class FilesystemCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilesystemCache
     */
    private $fileSystemCache;

    private $cacheKeyFixture = 'test-key';
    private $cacheValueFixture = ['foo' => 'bar'];

    public function setUp()
    {
        $this->fileSystemCache = new FilesystemCache(['cache_dir' => '~/spotify-refactoring-task/cache']);
    }

    public function testSetAndGet()
    {
        $this->fileSystemCache->set($this->cacheKeyFixture, $this->cacheValueFixture);
        $returnedValue = $this->fileSystemCache->get($this->cacheKeyFixture);
        $this->assertEquals($this->cacheValueFixture, $returnedValue);
    }

    public function testSetWithNoCacheDirSetScenario()
    {
        $this->setExpectedException(
            '\Spotify\RefactoringTask\CacheException',
            'Missing option - cache_dir.'
        );

        $fileSystemCache = new FilesystemCache([]);
        $fileSystemCache->set($this->cacheKeyFixture, $this->cacheValueFixture);
    }

    public function testSetWithCacheDirectoryCreationFailureScenario()
    {
        $this->setExpectedException(
            '\Spotify\RefactoringTask\CacheException',
            'Failed to create cache directory.'
        );

        $fileSystemCache = new FilesystemCache(['cache_dir' => '/crazy/path']);
        $fileSystemCache->set($this->cacheKeyFixture, $this->cacheValueFixture);
    }

    public function testSetWithFileCreationFailure()
    {
        $this->setExpectedException(
            '\Spotify\RefactoringTask\CacheException',
            'Failed to write cache content.'
        );

        $options = ['cache_dir' => '~/tpm/spotify-refactoring-task/cache'];
        $fileSystemCache = new FilesystemCache($options);
        $fileSystemCache->set($this->cacheKeyFixture, $this->cacheValueFixture);

        $filePath = "{$options['cache_dir']}/{$this->cacheKeyFixture}";
        chmod($filePath, 0000);
        $this->fileSystemCache->set($this->cacheKeyFixture, $this->cacheValueFixture);
    }
}