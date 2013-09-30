<?php

namespace Spotify\RefactoringTask\Test\Integration;

use Spotify\RefactoringTask\BitlyClient;
use Spotify\RefactoringTask\CurlHttpClient;
use Spotify\RefactoringTask\FilesystemCache;
use Spotify\RefactoringTask\HttpClientInterface;
use Spotify\RefactoringTask\HttpResponse;

class BitlyClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurlHttpClient
     */
    public $curlHttpClient;

    /**
     * @var BitlyClient
     */
    public $bitlyClient;

    public function setUp()
    {
        $this->curlHttpClient = new CurlHttpClient();
        $filesystemCache = new FilesystemCache(['cache_dir' => '~/tmp/spotify-refactoring-task/cache']);
        $this->bitlyClient = new BitlyClient($this->curlHttpClient, 'ee063c455bce24d14d21b5e17ec8dc76ef44f294', $filesystemCache);
    }

    public function testShorten()
    {
        $longUrl = 'http://yuriteixeira.com.br';
        $shortUrl = $this->bitlyClient->shorten($longUrl);

        $longUrlResponse = $this->curlHttpClient
            ->setMethod(HttpClientInterface::METHOD_GET)
            ->setUrl($longUrl)
            ->send();

        $shortUrlResponse = $this->curlHttpClient
            ->setMethod(HttpClientInterface::METHOD_GET)
            ->setUrl($shortUrl)
            ->send();

        $this->assertEquals($longUrlResponse, $shortUrlResponse);
    }
}