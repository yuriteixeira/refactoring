<?php

use Spotify\RefactoringTask\BitlyClient;
use Spotify\RefactoringTask\HttpResponse;

class BitlyClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    public $curlHttpClientMock;

    /**
     * @var BitlyClient
     */
    public $bitlyClient;

    public function setUp()
    {
        $this->curlHttpClientMock = $this->getMock('\Spotify\RefactoringTask\CurlHttpClient');
        $this->bitlyClient = new BitlyClient($this->curlHttpClientMock, 'ee063c455bce24d14d21b5e17ec8dc76ef44f294');
    }

    public function testShortenWithUnsuccessfullResponseStatusCodeScenario()
    {
        $response = new HttpResponse();
        $response->setStatusCode(500);

        $this->curlHttpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $this->setExpectedException(
            '\Spotify\RefactoringTask\BitlyException',
            'Unsuccessful response status code - 500'
        );

        $this->bitlyClient->shorten('http://yuriteixeira.com.br');
    }

    public function testShortenWithUnsetJsonStatusCodeScenario()
    {
        $response = new HttpResponse();
        $response
            ->setStatusCode(200)
            ->setBody(json_encode(['data' => ['url' => 'http://bit.ly/xyz']]));

        $this->curlHttpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $this->setExpectedException(
            '\Spotify\RefactoringTask\BitlyException',
            'Invalid response'
        );

        $this->bitlyClient->shorten('http://yuriteixeira.com.br');
    }

    public function testShortenWithUnsetJsonUrlScenario()
    {
        $response = new HttpResponse();
        $response
            ->setStatusCode(200)
            ->setBody(json_encode(['status_code' => 200]));

        $this->curlHttpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $this->setExpectedException(
            '\Spotify\RefactoringTask\BitlyException',
            'Invalid response'
        );

        $this->bitlyClient->shorten('http://yuriteixeira.com.br');
    }

    public function testShortenWithInvalidJsonStatusCodeScenario()
    {
        $response = new HttpResponse();
        $response
            ->setStatusCode(200)
            ->setBody(json_encode(['data' => ['url' => 'http://bit.ly/xyz'], 'status_code' => 500]));

        $this->curlHttpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $this->setExpectedException(
            '\Spotify\RefactoringTask\BitlyException',
            'Invalid response'
        );

        $this->bitlyClient->shorten('http://yuriteixeira.com.br');
    }

    public function testShortenWithInvalidUrlScenario()
    {
        $response = new HttpResponse();
        $response
            ->setStatusCode(200)
            ->setBody(json_encode(['data' => ['url' => ''], 'status_code' => 200]));

        $this->curlHttpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $this->setExpectedException(
            '\Spotify\RefactoringTask\BitlyException',
            'Invalid response'
        );

        $this->bitlyClient->shorten('http://yuriteixeira.com.br');
    }

    public function testShortenSuccessfullScenario()
    {
        $shortUrl = 'http://bit.ly/xyz';

        $response = new HttpResponse();
        $response
            ->setStatusCode(200)
            ->setBody(json_encode(['data' => ['url' => $shortUrl], 'status_code' => 200]));

        $this->curlHttpClientMock
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $returnedShortUrl = $this->bitlyClient->shorten('http://yuriteixeira.com.br');

        $this->assertEquals($shortUrl, $returnedShortUrl);
    }
}