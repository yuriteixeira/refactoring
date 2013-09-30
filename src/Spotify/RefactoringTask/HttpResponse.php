<?php

namespace Spotify\RefactoringTask;

class HttpResponse
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $body;

    /**
     * @param string $body
     * @return HttpResponse
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param int $statusCode
     * @return HttpResponse
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}