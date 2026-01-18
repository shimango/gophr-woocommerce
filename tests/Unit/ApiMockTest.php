<?php

namespace Gophr\Tests\Unit;

use Gophr\Tests\TestCase;
use Mockery;

class ApiMockTest extends TestCase
{

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_api_client_can_be_mocked()
    {
        $mock_client = Mockery::mock(\Shimango\Gophr\Client::class);

        $this->assertInstanceOf(\Shimango\Gophr\Client::class, $mock_client);
    }

    // Add more mock tests when you want to test API interactions without real calls
}