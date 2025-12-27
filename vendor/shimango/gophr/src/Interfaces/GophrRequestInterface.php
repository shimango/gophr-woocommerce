<?php

namespace Shimango\Gophr\Interfaces;

interface GophrRequestInterface
{
    public function getEndpoint(): string;

    public function setEndpoint(string $endpoint): GophrRequestInterface;

    public function getBaseUrl(): string;

    public function getApiVersion(): string;

    public function setHttpErrors(bool $http_errors): GophrRequestInterface;

    public function setApiKey(string $apiKey): GophrRequestInterface;

    public function setReturnType(string $returnClass): GophrRequestInterface;

    public function addHeaders(array $headers): GophrRequestInterface;

    public function getHeaders(): array;

    public function attachBody($obj);

    public function getBody();

    public function setTimeout(int $timeout): GophrRequestInterface;

    public function getTimeout(): int;

    public function execute(string $requestType): GophrResponseInterface;
}
