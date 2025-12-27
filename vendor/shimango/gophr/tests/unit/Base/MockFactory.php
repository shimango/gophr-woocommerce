<?php

namespace Shimango\Gophr\Tests\Unit\Base;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;
use Shimango\Gophr\Http\AbstractGophrResponse;
use Shimango\Gophr\Http\GophrRequest;
use Shimango\Gophr\Http\Responses\Jobs\CreateJobResponse;

class MockFactory extends TestCase
{
    /**
     * Get a mocked object of the given class and override any needed function.
     * @param string $class_name
     * @param array $constructor_args
     * @param array $functions_will_return
     * @return MockObject
     */
    public function getMockedObject(string $class_name, array $constructor_args = [], array $functions_will_return = []): MockObject
    {
        $mock_object = $this->getMockBuilder($class_name);

        (!empty($constructor_args)) ? $mock_object->setConstructorArgs($constructor_args) : $mock_object->disableOriginalConstructor();

        if (!empty($functions_will_return)) {
            $mock_object->onlyMethods(array_keys($functions_will_return));
        }

        $object = $mock_object->getMock();

        foreach ($functions_will_return as $function => $will_return) {
            $object->expects($this->atLeastOnce())->method($function)->willReturn($will_return);
        }

        return $object;
    }

    public function getGophrClient(?Configuration $config = null): Client
    {
        $config ??= new Configuration(Constants::API_KEY);
        return new Client($config);
    }

    public function getGophrRequest(?Configuration $config = null): GophrRequest
    {
        $config ??= new Configuration(Constants::API_KEY);
        return new GophrRequest(
            $config->getApiEndPoint(),
            $config->getApiKey(),
            $config->getBaseUrl(),
            $config->getApiVersion(),
            $config->getProxyPort(),
            $config->isProxyVerifySSL(),
        );
    }

    public function getGophrMockResponse(string $class): AbstractGophrResponse
    {
        return $this->getMockBuilder(CreateJobResponse::class)->disableOriginalConstructor()->getMock();
    }
}