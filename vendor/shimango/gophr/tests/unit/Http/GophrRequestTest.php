<?php
namespace Shimango\Gophr\Tests\Unit\Http;

use Shimango\Gophr\Exceptions\InvalidReturnTypeException;
use Shimango\Gophr\Exceptions\RequestException;
use Shimango\Gophr\Http\GophrRequest;
use Shimango\Gophr\Tests\Unit\Base\BaseTest;
use Shimango\Gophr\Tests\Unit\Base\Constants;

class GophrRequestTest extends BaseTest
{
    public function testAssertDefaultValuesAreSetForTheRequest(): void
    {
        $endpoint = '/some-endpoint';
        $request = new GophrRequest($endpoint, Constants::API_KEY, Constants::REST_BASE_URL, 'v2');

        $this->assertEquals($endpoint, $request->getEndpoint());
        $this->assertEquals(Constants::REST_BASE_URL, $request->getBaseUrl());
        $this->assertEquals('v2', $request->getApiVersion());

        $defaultHeaders = [
            'Host' => Constants::REST_BASE_URL,
            'Content-Type' => 'application/json',
            'API-KEY' => Constants::API_KEY,
        ];

        // Default values
        $this->assertEquals(100, $request->getTimeout());
        $this->assertEquals($defaultHeaders, $request->getHeaders());
        $this->assertNull($request->getBody());
    }

    public function testExceptionIsThrownForEmptyApiKey(): void
    {
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('No API Key has been provided');

        $endpoint = '/some-endpoint';
        $request = new GophrRequest($endpoint, '', Constants::REST_BASE_URL, 'v2');
    }

    public function testInvalidReturnTypeExceptionIsThrown(): void
    {
        $request = new GophrRequest('/some-endpoint', Constants::API_KEY, Constants::REST_BASE_URL, 'v2');

        $wrongReturnClass = Constants::class;

        $this->expectException(InvalidReturnTypeException::class);
        $this->expectExceptionMessage("{$wrongReturnClass} must be an instance of AbstractGophrResponse");

        $request->setReturnType($wrongReturnClass);
    }

    public function testTimeOutIsSetCorrectlyAndObjectIsImmutable(): void
    {
        $request = new GophrRequest('/some-endpoint', Constants::API_KEY, Constants::REST_BASE_URL, 'v2');

        $request1 = $request->setTimeout(60);
        $this->assertEquals(60, $request1->getTimeout());
        $this->assertNotSame($request, $request1);
    }

    public function testHttpErrorsAreSetCorrectlyAndObjectIsImmutable(): void
    {
        $request = new GophrRequest('/some-endpoint', Constants::API_KEY, Constants::REST_BASE_URL, 'v2');

        $request1 = $request->setHttpErrors(false);
        $this->assertNotSame($request, $request1);
    }

    public function testApiKeyIsSetCorrectlyAndObjectIsImmutable(): void
    {
        $request = new GophrRequest('/some-endpoint', '123456789', Constants::REST_BASE_URL, 'v2');

        $request1 = $request->setApiKey(Constants::API_KEY);
        $this->assertNotSame($request, $request1);
    }

    public function testHeadersAreSetCorrectlyAndObjectIsImmutable(): void
    {
        $request = new GophrRequest('/some-endpoint', Constants::API_KEY, Constants::REST_BASE_URL, 'v2');

        $headersAdd = ['test' => ['123']];
        $request1 = $request->addHeaders($headersAdd);

        $expectedHeaders = array_merge($request->getHeaders(), $headersAdd);
        $this->assertEquals($expectedHeaders, $request1->getHeaders());
        $this->assertNotSame($request, $request1);
    }

    public function testBodyIsSetCorrectlyAndObjectIsImmutable(): void
    {
        $request = new GophrRequest('/some-endpoint', Constants::API_KEY, Constants::REST_BASE_URL, 'v2');

        $request1 = $request->attachBody("{}");
        $request2 = $request1->attachBody(['test' => '123']);

        $this->assertNull($request->getBody());
        $this->assertEquals('{}', $request1->getBody());
        $this->assertEquals(json_encode(['test' => '123'], true), $request2->getBody());

        $this->assertNotSame($request, $request1);
        $this->assertNotSame($request1, $request2);
        $this->assertNotSame($request2, $request);
    }
}
