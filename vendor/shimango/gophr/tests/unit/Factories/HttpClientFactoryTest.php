<?php
namespace Shimango\Gophr\Tests\Unit\Factories;

use GuzzleHttp\Client;
use Shimango\Gophr\Common\Configuration;
use Shimango\Gophr\Factories\HttpClientFactory;
use Shimango\Gophr\Tests\Unit\Base\BaseTest;
use Shimango\Gophr\Tests\Unit\Base\Constants;

class HttpClientFactoryTest extends BaseTest
{
    public function testAssertCorrectObjectCreated(): void
    {
        $config = new Configuration(Constants::API_KEY);
        $httpClient = HttpClientFactory::makeGuzzleClient($config->getBaseUrl(), []);
        $this->assertInstanceOf(Client::class, $httpClient);
        $this->assertEquals($config->getBaseUrl(), $httpClient->getConfig('base_uri'));
    }

    public function testMakeGuzzleClientFromDefaultConfig(): void
    {
        $config = new Configuration(Constants::API_KEY);
        $httpClient = HttpClientFactory::makeGuzzleClient($config->getBaseUrl(), []);
        $this->assertTrue(method_exists($httpClient, 'getConfig')); // method is deprecated
    }

    public function testMakeGuzzleClientFromConfigSettings(): void
    {
        $config = new Configuration(Constants::API_KEY, true, 'v2', 2112, true);
        $httpClient = HttpClientFactory::makeGuzzleClient($config->getBaseUrl(), [], true, $config->isProxyVerifySSL(), $config->getProxyPort());
        $this->assertTrue(method_exists($httpClient, 'getConfig')); // method is deprecated

        $this->assertTrue($httpClient->getConfig('verify'));
        $this->assertEquals($config->getProxyPort(), $httpClient->getConfig('proxy'));
    }
}
