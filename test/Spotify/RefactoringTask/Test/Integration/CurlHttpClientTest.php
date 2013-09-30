<?php

namespace Spotify\RefactoringTask\Test\Integration;

use Spotify\RefactoringTask\CurlHttpClient;
use Spotify\RefactoringTask\HttpClientInterface;
use Spotify\RefactoringTask\HttpException;

class CurlHttpClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurlHttpClient
     */
    private $curlHttpClient;

    public function setUp()
    {
        $this->curlHttpClient = new CurlHttpClient();
    }

    public function testSetAndGetUrl()
    {
        $value = 'http://google.com';
        $this->curlHttpClient->setUrl($value);
        $this->assertEquals($value, $this->curlHttpClient->getUrl());
    }

    public function testSetAndGetMethod()
    {
        $value = HttpClientInterface::METHOD_POST;
        $this->curlHttpClient->setMethod($value);
        $this->assertEquals($value, $this->curlHttpClient->getMethod());
    }

    public function testSetAndGetPostParams()
    {
        $value = ['foo' => 'bar'];
        $this->curlHttpClient->setPostParams($value);
        $this->assertEquals($value, $this->curlHttpClient->getPostParams());
    }

    public function testSetAndGetRawRequestBody()
    {
        $value = '{"foo": "bar"}';
        $this->curlHttpClient->setRawRequestBody($value);
        $this->assertEquals($value, $this->curlHttpClient->getRawRequestBody());
    }

    public function testSetAndGetRequestHeaders()
    {
        $value = ['foo' => 'bar'];
        $this->curlHttpClient->setRequestHeaders($value);
        $this->assertEquals($value, $this->curlHttpClient->getRequestHeaders());
    }

    public function testUrlNotSetSendScenario()
    {
        $this->setExpectedException(
            '\Spotify\RefactoringTask\HttpException',
            'Url not set.',
            HttpException::ERROR_URL_NOT_SET
        );

        $this->curlHttpClient->send();
    }

    public function testPutOrPostButNoRequestDataSetSendScenario()
    {
        $this->setExpectedException(
            '\Spotify\RefactoringTask\HttpException',
            'POST or PUT method requires raw request body or post parameters to be set.',
            HttpException::ERROR_REQUEST_WITHOUT_DATA
        );

        $this->curlHttpClient
            ->setUrl('http://google.com')
            ->setMethod(HttpClientInterface::METHOD_POST)
            ->send();
    }

    public function testSendWithGetMethodScenario()
    {
        $response = $this->curlHttpClient
            ->setUrl('http://google.com')
            ->setMethod(HttpClientInterface::METHOD_GET)
            ->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
    }

    public function testSendWithPostMethodScenario()
    {
        $response = $this->curlHttpClient
            ->setUrl('http://google.com')
            ->setMethod(HttpClientInterface::METHOD_POST)
            ->setPostParams(['foo' => 'bar'])
            ->send();

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
    }
}