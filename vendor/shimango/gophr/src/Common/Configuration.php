<?php
namespace Shimango\Gophr\Common;

/**
 * Configuration Class
  * @category Class
 * @package  Shimango\Gophr
 */
final class Configuration
{
    /**
     * @var string
     */
    private const API_VERSION = "v2";

    /**
     * @var string
     */
    private const REST_BASE_URL = "https://api.gophr.com";

    /**
     * @var string
     */
    private const REST_BASE_URL_SANDBOX = "https://api-sandbox.gophr.com";

    /**
     * @var array<string, string>
     */
    private const ENDPOINTS = [
        'v2' => '/v2-commercial-api',
    ];

    /**
     * Creates a configuration object
     */
    public function __construct(private string $apiKey, private bool $isSandbox = false, private string $apiVersion = self::API_VERSION, private ?int $proxyPort = null, private bool $proxyVerifySSL = false)
    {
    }

    /**
     * Gets the API Key
     * @return string API token
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Sets the API Key
     */
    public function setApiKey(string $apiKey): Configuration
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Gets the API version
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Sets the API version (v2)
     */
    public function setApiVersion(string $apiVersion): Configuration
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * Checks if sandbox mode is set
     */
    public function isSandbox(): bool
    {
        return $this->isSandbox;
    }

    /**
     * Sets sandbox mode
     */
    public function setIsSandbox(bool $isSandbox): Configuration
    {
        $this->isSandbox = $isSandbox;
        return $this;
    }

    /**
     * Gets the proxy port
     */
    public function getProxyPort(): ?int
    {
        return $this->proxyPort;
    }

    /**
     * Sets the proxy port
     */
    public function setProxyPort(?int $proxyPort): Configuration
    {
        $this->proxyPort = $proxyPort;
        return $this;
    }

    /**
     * Checks if proxy SSL verification is set
     */
    public function isProxyVerifySSL(): bool
    {
        return $this->proxyVerifySSL;
    }

    /**
     * Sets proxy SSL verification
     */
    public function setProxyVerifySSL(bool $proxyVerifySSL): Configuration
    {
        $this->proxyVerifySSL = $proxyVerifySSL;
        return $this;
    }

    /**
     * Gets the base url
     */
    public function getBaseUrl(): string
    {
        if ($restBaseUrlDevelopment = getenv('REST_BASE_URL_DEVELOPMENT')) {
            return $restBaseUrlDevelopment;
        }

        return $this->isSandbox ? self::REST_BASE_URL_SANDBOX : self::REST_BASE_URL;
    }

    /**
     * Gets the API endpoint based on the API version
     */
    public function getApiEndPoint(): ?string
    {
        return self::ENDPOINTS[$this->apiVersion] ?? null;
    }
}
