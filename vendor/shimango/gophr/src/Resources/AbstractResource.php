<?php

namespace Shimango\Gophr\Resources;

use Shimango\Gophr\Exceptions\InvalidReturnTypeException;
use Shimango\Gophr\Http\AbstractGophrResponse;
use Shimango\Gophr\Http\GophrRequest;

/**
 * Abstract Resource
 * @package Shimango\Gophr\Resources
 */
abstract class AbstractResource
{
    /**
     * @var string
     */
    public const REQUEST_GET = "GET";

    /**
     * @var string
     */
    public const REQUEST_POST = "POST";

    /**
     * @var string
     */
    public const REQUEST_PATCH = "PATCH";

    /**
     * @var string
     */
    public const REQUEST_DELETE = "DELETE";

    public function __construct(protected GophrRequest $gophrRequest)
    {
    }

    /**
     * Posts
     * @throws InvalidReturnTypeException
     */
    public function create(string $endPoint, array $body, ?string $dtoResponseClass = null): AbstractGophrResponse
    {
        $body['meta_data']['booking_method'] ??= 'shimango';
        return $this->gophrRequest->setEndpoint($endPoint)->attachBody($body)->setReturnType($dtoResponseClass)->execute(self::REQUEST_POST);
    }

    /**
     * Gets
     * @throws InvalidReturnTypeException
     */
    public function read(string $endPoint, ?string $dtoResponseClass = null): AbstractGophrResponse
    {
        return $this->gophrRequest->setEndpoint($endPoint)->setReturnType($dtoResponseClass)->execute(self::REQUEST_GET);
    }

    /**
     * Updates
     * @throws InvalidReturnTypeException
     */
    public function update(string $endPoint, array $body, ?string $dtoResponseClass = null): AbstractGophrResponse
    {
        return $this->gophrRequest->setEndpoint($endPoint)->attachBody($body)->setReturnType($dtoResponseClass)->execute(self::REQUEST_PATCH);
    }

    /**
     * Deletes
     * @throws InvalidReturnTypeException
     */
    public function delete(string $endPoint, ?string $dtoResponseClass = null): AbstractGophrResponse
    {
        return $this->gophrRequest->setEndpoint($endPoint)->setReturnType($dtoResponseClass)->execute(self::REQUEST_DELETE);
    }

    /**
     * Given a key value array, this function generates a URL-encoded query string from its contents.
     */
    protected function generateUrlParameters(array $parameters = []): string
    {
        if ($xdebugSession = getenv('GOPHR_XDEBUG_SESSION')) {
            $parameters['XDEBUG_SESSION'] = $xdebugSession;
        }

        return $parameters === [] ? '' : sprintf('?%s', http_build_query($parameters));
    }
}
