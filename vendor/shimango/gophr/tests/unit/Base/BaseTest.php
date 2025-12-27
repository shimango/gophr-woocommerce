<?php
namespace Shimango\Gophr\Tests\Unit\Base;

use DG\BypassFinals;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Shimango\Gophr\Client;
use Shimango\Gophr\Factories\ResourceFactory;
use Shimango\Gophr\Resources\Delivery;
use Shimango\Gophr\Resources\Job;
use Shimango\Gophr\Resources\Parcel;

abstract class BaseTest extends TestCase
{
    protected MockFactory $mockFactory;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mockFactory = new MockFactory();
    }

    public function setUp(): void
    {
        parent::setUp();
        BypassFinals::enable();
    }

    /**
     * @throws ReflectionException
     */
    protected function setProtectedProperty($object, string $property, $value)
    {
        $reflection = new ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }

    protected function getClientForJobTest(string $verb, string $responseClass, string $expectedApiEndPoint, ?array $jobData = null): Client
    {
        return $this->getClientForTest($verb, Job::class, $responseClass, $expectedApiEndPoint, $jobData);
    }

    protected function getClientForDeliveryTest(string $verb, string $responseClass, string $expectedApiEndPoint, ?array $jobData = null): Client
    {
        return $this->getClientForTest($verb, Delivery::class, $responseClass, $expectedApiEndPoint, $jobData);
    }

    protected function getClientForParcelTest(string $verb, string $responseClass, string $expectedApiEndPoint, ?array $jobData = null): Client
    {
        return $this->getClientForTest($verb, Parcel::class, $responseClass, $expectedApiEndPoint, $jobData);
    }

    private function getClientForTest(string $verb, string $resourceClass, string $responseClass, string $expectedApiEndPoint, ?array $data = null): Client
    {
        static $dataParamName = [Job::class => 'jobData', Delivery::class => 'deliveryData', Parcel::class => 'parcelData'];
        static $resourceFactoryMethodCall = [Job::class => 'makeJobResource', Delivery::class => 'makeDeliveryResource', Parcel::class => 'makeParcelResource'];

        $client = $this->mockFactory->getGophrClient();
        $gophrRequest = $this->mockFactory->getGophrRequest();

        $mockGophrResponse = $this->mockFactory->getMockedObject($responseClass);

        $mockJobResource = $this->mockFactory->getMockedObject($resourceClass, ['gophrRequest' => $gophrRequest], [$verb => $mockGophrResponse]);
        $functionArgs = ['apiEndPoint' => $expectedApiEndPoint];
        if ($data) {
            $functionArgs[$dataParamName[$resourceClass]] = $data;
        }
        $mockJobResource->expects($this->once())->method($verb)->withConsecutive($functionArgs);

        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)
            ->onlyMethods([$resourceFactoryMethodCall[$resourceClass]])
            ->getMock();

        $mockResourceFactory->method($resourceFactoryMethodCall[$resourceClass])->willReturn($mockJobResource);

        $this->setProtectedProperty($client, 'resourceFactory', $mockResourceFactory);

        return $client;
    }
}
