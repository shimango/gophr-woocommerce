<?php

namespace Shimango\Gophr\Factories;

use Shimango\Gophr\Common\Configuration;
use Shimango\Gophr\Exceptions\GophrException;
use Shimango\Gophr\Http\GophrRequest;

final class RequestFactory
{
    /**
     * Makes a Gophr request
     * @throws GophrException
     */
    public static function makeGophrRequest(Configuration $configuration): GophrRequest
    {
        return new GophrRequest(
            $configuration->getApiEndPoint(),
            $configuration->getApiKey(),
            $configuration->getBaseUrl(),
            $configuration->getApiVersion(),
            $configuration->getProxyPort(),
            $configuration->isProxyVerifySSL(),
        );
    }
}
