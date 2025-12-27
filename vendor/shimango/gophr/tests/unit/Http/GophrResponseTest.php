<?php
namespace Shimango\Gophr\Tests\Unit\Http;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;
use Shimango\Gophr\Http\GophrResponse;
use Shimango\Gophr\Tests\Unit\Base\BaseTest;
use Shimango\Gophr\Tests\Unit\Base\Constants;

class GophrResponseTest extends BaseTest
{
    public function testJobResourceValues(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class, [], ['getContents' => '{"name":"John", "surname":"Smith", "age":33}']);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY]);

        $this->assertIsArray($response->getHeaders());
        $this->assertTrue($response->hasHeader('API-KEY'));
        $this->assertEquals(Constants::API_KEY, $response->getHeaderLine('API-KEY'));
        $this->assertEquals(Constants::API_KEY, $response->getHeader('API-KEY')[0]);
        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertIsArray($body);
        $this->assertEquals($body['name'], "John");
        $this->assertEquals($body['surname'], "Smith");
        $this->assertEquals($body['age'], 33);
    }

    public function testJobResourceTypes(): void
    {
        $bodyContents = ['name' => 'John', 'surname' => 'Smith', 'age' => 33];

        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class, [], ['getContents' => json_encode($bodyContents)]);
        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY]);

        $header = $response->getHeader('non-existing-header');
        $this->assertIsArray($header);
        $this->assertEmpty($header);

        $contentsArray = $response->getContentsArray();

        $this->assertIsArray($contentsArray);
        $this->assertEquals($bodyContents, $contentsArray);
    }

    public function testAssertCorrectProtocolVersionIsSet(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY]);
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertEquals('1.2', $response->withProtocolVersion('1.2')->getProtocolVersion());
    }

    public function testAssertCorrectHeaderValuesAreSetCorrectly(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $headers = ['API-KEY' => Constants::API_KEY];
        $response = new GophrResponse($mockBody, 200, $headers);
        $response1 = $response->withHeader('headerKey', ['headerValue']);

        $this->assertArrayHasKey('headerKey', $response1->getHeaders());
        $this->assertEquals('headerValue', $response1->getHeaderLine('headerKey'));
        $this->assertEquals('headerValue', $response1->getHeader('headerKey')[0]);

        $response2 = $response->withHeader('headerKey', ['headerValue1', 'headerValue2']);
        $this->assertEquals('headerValue1,headerValue2', $response2->getHeaderLine('headerKey'));
        $this->assertNotSame($response, $response1);
    }

    public function testAssertWithAddedHeadersMergesHeaderValues(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY, 'dogs' => ['Bytor']]);
        $response1 = $response->withAddedHeader('dogs', ['Snow dog']);

        $this->assertEquals('Bytor,Snow dog', $response1->getHeaderLine('dogs'));
        $this->assertNotSame($response, $response1);
    }

    public function testAssertWithoutHeadersRemovesHeaderValue(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY, 'dogs' => ['Bytor']]);
        $response1 = $response->withoutHeader('dogs');

        $this->assertEquals('', $response1->getHeaderLine('dogs'));
        $this->assertNotSame($response, $response1);
    }

    public function testAssertWithStatusWorks(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY, 'dogs' => ['Bytor']]);
        $response1 = $response->withStatus(404, 'Resource not found');

        $this->assertEquals(404, $response1->getStatusCode());
        $this->assertEquals('Resource not found', $response1->getReasonPhrase());
        $this->assertNotSame($response, $response1);
    }

    public function testAssertGetContentsObjectReturnsNullForParentClass(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY, 'dogs' => ['Bytor']]);
        $this->assertNull($response->getContentsObject());
    }

    public function testWithBodyFunctionSetsTheBodyCorrectly(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY, 'dogs' => ['Bytor']]);
        $this->assertSame($mockBody, $response->getBody());

        /** @var StreamInterface $mockNewBody */
        $mockNewBody = $this->mockFactory->getMockedObject(Stream::class);
        $response1 = $response->withBody($mockNewBody);
        $this->assertSame($mockNewBody, $response1->getBody());
    }

    public function testgetContentsObjectIsNullByDefault(): void
    {
        /** @var StreamInterface $mockBody */
        $mockBody = $this->mockFactory->getMockedObject(Stream::class);

        $response = new GophrResponse($mockBody, 200, ['API-KEY' => Constants::API_KEY, 'dogs' => ['Bytor']]);
        $this->assertNull($response->getContentsObject());
    }
}
