<?php

namespace Spotify\RefactoringTask;

class CurlHttpClient implements HttpClientInterface
{
    private $url;
    private $method;
    private $postParams;
    private $rawRequestBody;
    private $requestHeaders;

    /**
     * @param string $url
     * @return CurlHttpClient
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param string $method (GET, POST, PUT or DELETE)
     * @return CurlHttpClient
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param array $params
     * @return CurlHttpClient
     */
    public function setPostParams(array $params)
    {
        $this->postParams = $params;
        return $this;
    }

    /**
     * @param string $rawRequestBody
     * @return CurlHttpClient
     */
    public function setRawRequestBody($rawRequestBody)
    {
        $this->rawRequestBody = $rawRequestBody;
        return $this;
    }

    /**
     * @param array $requestHeaders
     * @return CurlHttpClient
     */
    public function setRequestHeaders(array $requestHeaders)
    {
        $this->requestHeaders = $requestHeaders;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    /**
     * @return string
     */
    public function getRawRequestBody()
    {
        return $this->rawRequestBody;
    }

    /**
     * @return array
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * Send the request and get the response body
     *
     * @return HttpResponse
     * @throws HttpException
     */
    public function send()
    {
        // Checks
        if (!$this->getUrl()) {
            throw new HttpException('Url not set.', HttpException::ERROR_URL_NOT_SET);
        }

        $postParams = $this->getPostParams();
        $hasNoDataSet =
            ($this->getMethod() == static::METHOD_PUT || $this->getMethod() == static::METHOD_POST) &&
            (!$this->getRawRequestBody() && empty($postParams));

        if ($hasNoDataSet) {
            throw new HttpException('POST or PUT method requires raw request body or post parameters to be set.', HttpException::ERROR_REQUEST_WITHOUT_DATA);
        }

        // Issuing the request
        $ch = curl_init($this->getUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // PUT or DELETE method
        if ($this->getMethod() == static::METHOD_PUT || $this->getMethod() == static::METHOD_DELETE) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->getMethod());
        }

        // Request Payload
        if ($this->getMethod() == static::METHOD_POST || $this->getMethod() == static::METHOD_PUT) {
            $data = $this->getRawRequestBody() ?: http_build_query($this->getPostParams());
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        // Headers
        $headers = $this->getRequestHeaders();
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Handling errors
        $result = curl_exec($ch);
        $errorMessage = curl_error($ch);
        $errorCode = curl_errno($ch);

        if ($errorMessage || $errorCode) {
            $errorMessage = $errorMessage ?: 'Undefined Error';
            throw new HttpException($errorMessage, $errorCode);
        }

        // All good? Returning a HttpResponse instance
        $response = new HttpResponse();
        $response
            ->setStatusCode(curl_getinfo($ch, CURLINFO_HTTP_CODE))
            ->setBody($result);

        return $response;
    }
}