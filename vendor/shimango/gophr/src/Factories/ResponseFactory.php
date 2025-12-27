<?php

namespace Shimango\Gophr\Factories;

use Shimango\Gophr\Http\AbstractGophrResponse;
use Psr\Http\Message\StreamInterface;

final class ResponseFactory
{
    /**
     * Makes a Gophr request
     */
    public static function makeGophrResponse(StreamInterface $stream, int $httpStatusCode, array $headers, string $dtoReturnType): AbstractGophrResponse
    {
            return new $dtoReturnType($stream, $httpStatusCode, $headers);
    }
}
