<?php

namespace Spotify\RefactoringTask\Test\Unit;

use Spotify\RefactoringTask\HttpResponse;

class HttpResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpResponse
     */
    public $httpResponse;

    public function setUp()
    {
        $this->httpResponse = new HttpResponse();
    }

    public function testGetAndSetBody()
    {
        $value = '{"foo": "bar"}';
        $this->httpResponse->setBody($value);
        $this->assertEquals($value, $this->httpResponse->getBody());
    }

    public function testGetAndSetStatusCode()
    {
        $value = 200;
        $this->httpResponse->setStatusCode($value);
        $this->assertEquals($value, $this->httpResponse->getStatusCode());
    }
}