<?php

namespace ComplyCube;

use GuzzleHttp\Client;
use ComplyCube\ApiResponse;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use ComplyCube\Exception\ComplyCubeClientException;
use ComplyCube\Exception\ComplyCubeServerException;

class ApiClient
{
    /** @var integer */
    const VERSION = '1.0.0';
    
    /** @var string ComplyCube API key from developer dashboard */
    private $apiKey;
    
    /** @var string base URL for ComplyCube API. */
    const BASEURL = 'https://api.complycube.com/v1';

    /** @var \GuzzleHttp\ClientInterface Guzzle Http Client used to make requests */
    public $httpClient;

    public static function randomJitter($numRequests, $response): float
    {
        return (float) rand(0, $numRequests ** 1.5);
    }
    /**
     * Create instance of ComplyCube API client
     *
     * @param string $apiKey the ComplyCube API key from your developer dashboard.
     */
    public function __construct(string $apiKey, $maxRetries = 0)
    {
        if (!isset($this->httpClient)) {
            $stack = HandlerStack::create();
            $stack->push(GuzzleRetryMiddleware::factory([
                'max_retry_attempts' => $maxRetries,
                'default_retry_multiplier' => [ApiClient::class, 'randomJitter']]));
            $this->httpClient = new Client(['headers' => [
                'Authorization' => $apiKey,
                'User-Agent' => 'complycube-php/'.self::VERSION,
                'Content-Type' => 'application/json'],
                'handler' => $stack]);
        }
    }

    /**
     * Get request against ComplyCube API for given endpoint class.
     *
     * @param string $endpoint api endpoint class.
     * @param array $queryParams array of url encoding params.
     * @return ApiResponse the result object to map to model.
     */
    public function get(string $endpoint, array $queryParams = []): ApiResponse
    {
        return $this->sendRequest($endpoint, 'GET', $queryParams);
    }

    /**
     * Post request against ComplyCube API for given endpoint class.
     *
     * @param string $endpoint api endpoint class.
     * @param array $queryParams array of url encoding params.
     * @param string $data body of post
     * @return ApiResponse the result object to map to model.
     */
    public function post(string $endpoint, array $options, $data = null): ApiResponse
    {
        return $this->sendRequest($endpoint, 'POST', $options, $data);
    }

    /**
     * Delete request against API for given endpoint class
     *
     * @param string $endpoint api endpoint class.
     * @param array $options array of request options.
     * @return void
     */
    public function delete(string $endpoint, array $options = []): void
    {
        $this->sendRequest($endpoint, 'DELETE', $options);
    }

    /**
     * Send request to ComplyCube API.
     *
     * @param string $endpoint endpoint route.
     * @param string $method HTTP method.
     * @param array $options array of url encoding params and request options.
     * @param mixed $data object to be encoded to message body.
     * @return ApiResponse the result object to map to model.
     */
    public function sendRequest(string $endpoint, string $method, array $options = [], $data = null): ApiResponse
    {
        if (\in_array($method, ['POST','PUT'], true)) {
            $options['json'] = $data;
        }
        try {
            $rawResponse = $this->httpClient->request($method, $this::BASEURL.'/'.$endpoint, $options);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            throw new ComplyCubeClientException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (\GuzzleHttp\Exception\ServerException $exception) {
            throw new ComplyCubeServerException($exception->getMessage(), $exception->getCode(), $exception);
        }
        $response = new ApiResponse($rawResponse->getStatusCode(), $rawResponse->getBody(), $rawResponse->getHeaders());
        return $response;
    }
}
