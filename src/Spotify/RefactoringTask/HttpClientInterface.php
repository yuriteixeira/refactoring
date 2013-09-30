<?php

namespace Spotify\RefactoringTask;

interface HttpClientInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @param string $url
     */
    public function setUrl($url);

    /**
     * @param string $method (GET, POST, PUT or DELETE)
     */
    public function setMethod($method);

    /**
     * @param array $params
     */
    public function setPostParams(array $params);

    /**
     * @param string $rawRequestBody
     */
    public function setRawRequestBody($rawRequestBody);

    /**
     * @param array $requestHeaders
     */
    public function setRequestHeaders(array $requestHeaders);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getPostParams();

    /**
     * @return string
     */
    public function getRawRequestBody();

    /**
     * @return array
     */
    public function getRequestHeaders();

    /**
     * Send the request and get the response body
     * @return HttpResponse
     * @throws HttpException
     */
    public function send();
}