<?php
namespace Shimango\Gophr\Tests\Unit\Factories;

use Shimango\Gophr\Common\Configuration;
use Shimango\Gophr\Factories\ResourceFactory;
use Shimango\Gophr\Resources\Delivery;
use Shimango\Gophr\Resources\Job;
use Shimango\Gophr\Resources\Parcel;
use Shimango\Gophr\Tests\Unit\Base\BaseTest;
use Shimango\Gophr\Tests\Unit\Base\Constants;

class ResourceFactoryTest extends BaseTest
{
    public function testMakeJobResource(): void
    {
        $config = new Configuration(Constants::API_KEY);
        $resourceFactory = new ResourceFactory();
        $jobResource = $resourceFactory->makeJobResource($config);
        $this->assertInstanceOf(Job::class, $jobResource);
    }

    public function testMakeDeliveryResource(): void
    {
        $config = new Configuration(Constants::API_KEY);
        $resourceFactory = new ResourceFactory();
        $jobResource = $resourceFactory->makeDeliveryResource($config);
        $this->assertInstanceOf(Delivery::class, $jobResource);
    }

    public function testMakeParcelResource(): void
    {
        $config = new Configuration(Constants::API_KEY);
        $resourceFactory = new ResourceFactory();
        $jobResource = $resourceFactory->makeParcelResource($config);
        $this->assertInstanceOf(Parcel::class, $jobResource);
    }
}
