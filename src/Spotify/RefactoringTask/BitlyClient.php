<?php

namespace Spotify\RefactoringTask;

/**
 * Bitly API Client
 */
class BitlyClient
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $token = 'ee063c455bce24d14d21b5e17ec8dc76ef44f294';

    /**
     * @var string
     */
    private $apiUrl = 'https://api-ssl.bitly.com/v3';

    /**
     * @param HttpClientInterface $httpClient An instance of a HttpClientInterface implementation
     * @param $token Bitly Dev Token
     */
    public function __construct(HttpClientInterface $httpClient, $token) {
        $this->httpClient = $httpClient;
        $this->token = $token;
    }

    /**
     * Returns a long url shortened
     *
     * @param $longUrl Long url that will be shortened
     *
     * @return string
     *
     * @throws BitlyException
     * @throws HttpException
     */
    public function shorten($longUrl) {
        $endpointUrl = "{$this->apiUrl}/shorten?access_token={$this->token}&longUrl=" . urlencode($longUrl);

        $this->httpClient->setUrl($endpointUrl);
        $this->httpClient->setMethod(HttpClientInterface::METHOD_GET);

        $response = $this->httpClient->send();

        if ($response->getStatusCode() != 200) {
            throw new BitlyException('Unsuccessful response status code - ' . $response->getStatusCode());
        }

        $jsonResponse = json_decode($response->getBody());

        $isJsonResponseInvalid =
            !isset($jsonResponse->status_code) ||
            !isset($jsonResponse->data->url) ||
            $jsonResponse->status_code != 200 ||
            !$jsonResponse->data->url;

        if ($isJsonResponseInvalid) {
            throw new BitlyException('Invalid response');
        }

        return $jsonResponse->data->url;
    }
}