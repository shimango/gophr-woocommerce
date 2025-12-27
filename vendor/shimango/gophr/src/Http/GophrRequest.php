<?php

namespace Shimango\Gophr\Http;

use Shimango\Gophr\Exceptions\GophrException;
use Shimango\Gophr\Exceptions\InvalidReturnTypeException;
use Shimango\Gophr\Exceptions\RequestException;
use Shimango\Gophr\Factories\HttpClientFactory;
use Shimango\Gophr\Factories\ResponseFactory;
use Shimango\Gophr\Interfaces\GophrRequestInterface;
use Shimango\Gophr\Interfaces\GophrResponseInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

final class GophrRequest implements GophrRequestInterface
{
    /**
     * An array of headers to send with the request
     *
     * @var array(string => string)
     */
    private array $headers = [];

    /**
     * The body of the request (optional)
     */
    private ?string $requestBody = null;

    /**
     * The return type to cast the response as
     *
     */
    private ?string $returnType = null;


    private int $timeout;

    /**
     * Request options to decide if Guzzle Client should throw exceptions when http code is 4xx or 5xx
     */
    private bool $http_errors = true;

    /**
     * Constructs a new Gophr Request object
     *
     * @param string $endpoint     The Gophr endpoint to call
     * @param string $apiKey       A valid API Key to validate the Gophr call
     * @param string $baseUrl      The base URL to call
     * @param string $apiVersion   The API version to use
     * @param ?int $proxyPort    The url where to proxy through
     * @param bool $proxyVerifySSL Whether the proxy requests should perform SSL verification
     * @throws GophrException when no access token is provided
     */
    public function __construct(/**
     * The endpoint to call
     */
    protected string $endpoint, /**
     * A valid API Key token
     */
    protected string $apiKey, /**
     * The base url to call
     */
    protected string $baseUrl, /**
     * The API version to use ("v2", "beta")
     */
    protected string $apiVersion, /**
     * The proxy port to use. Null to disable
     */
    protected ?int $proxyPort = null, /**
     * Whether SSL verification should be used for proxy requests
     */
    protected bool $proxyVerifySSL = false)
    {
        if ($this->apiKey === '') {
            throw new RequestException("No API Key has been provided");
        }

        $this->timeout = 100;
        $this->headers = $this->getDefaultHeaders();
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): GophrRequest
    {
        $new = clone $this;
        $new->endpoint = $endpoint;
        return $new;
    }

    /**
     * Gets the Base URL the request is made to
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Gets the API version in use for the request
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Sets a http errors option
     * @param bool $http_errors A bool option to the Gophr call
     * @return GophrRequest object
     */
    public function setHttpErrors(bool $http_errors): GophrRequest
    {
        $new = clone $this;
        $new->http_errors = $http_errors;
        return $new;
    }

    /**
     * Sets a new API Key
     * @param string $apiKey A valid Api key to validate the Gophr call
     * @return GophrRequest object
     */
    public function setApiKey(string $apiKey): GophrRequest
    {
        $new = clone $this;
        $new->apiKey = $apiKey;
        $new->headers['API-KEY'] = $new->apiKey;
        return $new;
    }

    /**
     * Sets the return type of the response object
     * @param mixed $returnClass The object class to use
     * @return $this object
     * @throws InvalidReturnTypeException
     */
    public function setReturnType($returnClass): GophrRequest
    {
        if (!is_a($returnClass, AbstractGophrResponse::class, true)) {
            throw new InvalidReturnTypeException(sprintf('%s must be an instance of AbstractGophrResponse', $returnClass));
        }

        $new = clone $this;
        $new->returnType = $returnClass;
        return $new;
    }

    /**
     * Adds custom headers to the request
     *
     * @param array $headers An array of custom headers
     *
     * @return GophrRequest object
     */
    public function addHeaders(array $headers): GophrRequest
    {
        $new = clone $this;
        $new->headers = array_merge($new->headers, $headers);
        return $new;
    }

    /**
     * Get the request headers
     *
     * @return array of headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Attach a body to the request.
     *
     * @param mixed $obj The object to include in the request
     *
     * @return GophrRequest object
     */
    public function attachBody($obj): GophrRequest
    {
        $new = clone $this;

        // Attach streams & JSON automatically
        if (is_string($obj) || is_a($obj, StreamInterface::class)) {
            $new->requestBody = $obj;
        }
        // By default, JSON-encode
        else {
            $new->requestBody = json_encode($obj, JSON_THROW_ON_ERROR);
        }

        return $new;
    }

    /**
     * Get the body of the request
     * @return mixed request body of any type
     */
    public function getBody(): ?string
    {
        return $this->requestBody;
    }

    /**
     * Sets the timeout limit of the cURL request
     * @param int $timeout The timeout in seconds
     * @return GophrRequest object
     */
    public function setTimeout(int $timeout): GophrRequest
    {
        $new = clone $this;
        $new->timeout = $timeout;
        return $new;
    }

    /**
     * Gets the timeout value of the request
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Executes the HTTP request using Guzzle
     *
     * @return mixed object or array of objects of class $returnType
     */
    public function execute(string $requestType): GophrResponseInterface
    {
        try {
        $client = HttpClientFactory::makeGuzzleClient($this->baseUrl, $this->headers, $this->http_errors, $this->proxyVerifySSL, $this->proxyPort);
            $response = $client->request(
                $requestType,
                $this->endpoint,
                [
                    'body' => $this->requestBody,
                    'timeout' => $this->timeout
                ]
            );
        } catch(BadResponseException $badResponseException) {
            return ResponseFactory::makeGophrResponse(
                $badResponseException->getResponse()->getBody(),
                0,
                [],
                $this->returnType
            )->withStatus($badResponseException->getResponse()->getStatusCode(), $badResponseException->getResponse()->getReasonPhrase());
        } catch (GuzzleException $guzzleException) {
            return ResponseFactory::makeGophrResponse(
                Utils::streamFor(''),
                $guzzleException->getCode(),
                [],
                $this->returnType
            );
        }

        // Wrap response in CreateJobResponse layer
        return ResponseFactory::makeGophrResponse(
            $response->getBody(),
            $response->getStatusCode(),
            $response->getHeaders(),
            $this->returnType
        );
    }

    /**
     * Get a list of headers for the request
     *
     * @return array The headers for the request
     */
    private function getDefaultHeaders(): array
    {
        return [
            'Host' => $this->baseUrl,
            'Content-Type' => 'application/json',
            'API-KEY' => $this->apiKey
        ];
    }
}
