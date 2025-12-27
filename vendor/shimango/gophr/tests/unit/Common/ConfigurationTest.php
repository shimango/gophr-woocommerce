<?php
namespace Shimango\Gophr\Tests\Unit\Common;

use Shimango\Gophr\Common\Configuration;
use Shimango\Gophr\Tests\Unit\Base\BaseTest;
use Shimango\Gophr\Tests\Unit\Base\Constants;

class ConfigurationTest extends BaseTest
{
    public function testApiKeyIsSetCorrectly(): void
    {
        $config = new Configuration(Constants::API_KEY);
        $this->assertEquals(Constants::API_KEY, $config->getApiKey());
    }

    public function testDefaultValuesAreSetCorrectly(): void
    {
        $config = new Configuration(Constants::API_KEY);

        $this->assertFalse($config->isSandbox());
        $this->assertEquals('v2', $config->getApiVersion());
        $this->assertNull($config->getProxyPort());
        $this->assertFalse($config->isProxyVerifySSL());
        $this->assertEquals('/v2-commercial-api', $config->getApiEndPoint());
        $this->assertEquals(Constants::REST_BASE_URL, $config->getBaseUrl());
    }

    public function testValuesAreSetCorrectlyViaConstructor(): void
    {
        $isSandbox = true;
        $apiVersion = 'v2';
        $proxyPort = 2112;
        $proxyVerifySSL = true;
        $config = new Configuration(Constants::API_KEY, $isSandbox, $apiVersion, $proxyPort, $proxyVerifySSL);

        $this->assertTrue($config->isSandbox());
        $this->assertEquals('v2', $config->getApiVersion());
        $this->assertEquals($proxyPort, $config->getProxyPort());
        $this->assertTrue($config->isProxyVerifySSL());
        $this->assertEquals('/v2-commercial-api', $config->getApiEndPoint());
        $this->assertEquals(Constants::REST_BASE_URL_SANDBOX, $config->getBaseUrl());

    }

    public function testValuesAreSetCorrectlyViaSetMethod(): void
    {
        $config = new Configuration(Constants::API_KEY);

        $newApiKey = '0123456789abcdefghijklmnopqrstuvxz';
        $config->setApiKey($newApiKey);
        $this->assertEquals($newApiKey, $config->getApiKey());

        $config->setApiVersion('test');
        $this->assertEquals('test', $config->getApiVersion());

        $config->setIsSandbox(true);
        $this->assertTrue($config->isSandbox());
        $this->assertEquals(Constants::REST_BASE_URL_SANDBOX, $config->getBaseUrl());

        $config->setProxyPort(2113);
        $this->assertEquals(2113, $config->getProxyPort());

        $config->setProxyVerifySSL(true);
        $this->assertTrue($config->isProxyVerifySSL());

        $this->assertEquals(Constants::REST_BASE_URL_SANDBOX, $config->getBaseUrl());

        // The API version is invalid so there isn't an endpoint for it
        $this->assertNull($config->getApiEndPoint());
    }
}
