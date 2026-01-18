<?php
namespace Gophr\Tests\Unit;

use Gophr\Tests\TestCase;
use Gophr\Woocommerce\Utils\Payload;

class PayloadTest extends TestCase
{

    public function test_get_parcels_payload_returns_array()
    {
        $product = $this->create_test_product([
            'weight' => '2',
            'length' => '20',
            'width' => '15',
            'height' => '10',
        ]);

        $package = $this->create_shipping_package([$product]);

        $parcels = Payload::getParcelsPayload($package);

        $this->assertIsArray($parcels);
        $this->assertNotEmpty($parcels);
    }

    public function test_parcel_has_correct_dimensions()
    {
        $product = $this->create_test_product([
            'weight' => '2.5',
            'length' => '20',
            'width' => '15',
            'height' => '10',
        ]);

        $package = $this->create_shipping_package([$product]);
        $parcels = Payload::getParcelsPayload($package);

        $this->assertCount(1, $parcels);

        $parcel = $parcels[0];
        $this->assertEquals(20.0, $parcel->length);
        $this->assertEquals(15.0, $parcel->width);
        $this->assertEquals(10.0, $parcel->height);
        $this->assertEquals(2.5, $parcel->weight);
    }

    public function test_parcel_dimensions_multiply_by_quantity()
    {
        $product = $this->create_test_product([
            'weight' => '1',
            'length' => '10',
            'width' => '10',
            'height' => '10',
        ]);

        $package = [
            'contents' => [
                [
                    'key' => 'item_1',
                    'product_id' => $product->get_id(),
                    'quantity' => 3,
                    'data' => $product,
                ],
            ],
            'destination' => [],
        ];

        $parcels = Payload::getParcelsPayload($package);

        $parcel = $parcels[0];
        $this->assertEquals(30.0, $parcel->length); // 10 * 3
        $this->assertEquals(30.0, $parcel->width);
        $this->assertEquals(30.0, $parcel->height);
        $this->assertEquals(3.0, $parcel->weight);
    }

    public function test_get_request_payload_returns_dto()
    {
        $package = $this->create_shipping_package();
        $package['external_id'] = 'test_order_123';

        $payload = Payload::getRequestPayload($package);

        $this->assertInstanceOf(
            \Shimango\Gophr\DataTransferObjects\Request\Jobs\CreateJobRequestDto::class,
            $payload
        );
    }

    public function test_request_payload_has_pickups_and_dropoffs()
    {
        $package = $this->create_shipping_package();
        $package['external_id'] = 'test_order_123';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $this->assertArrayHasKey('pickups', $array);
        $this->assertArrayHasKey('dropoffs', $array);
        $this->assertNotEmpty($array['pickups']);
        $this->assertNotEmpty($array['dropoffs']);
    }

    public function test_payload_includes_external_id()
    {
        $package = $this->create_shipping_package();
        $package['external_id'] = 'order_12345';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $this->assertEquals('order_12345', $array['external_id']);
    }

    public function test_payload_uses_store_settings_for_pickup()
    {
        update_option('woocommerce_store_address', '789 Store Avenue');
        update_option('woocommerce_store_city', 'Manchester');
        update_option('woocommerce_store_postcode', 'M1 1AA');

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $pickup = $array['pickups'][0];
        $this->assertEquals('789 Store Avenue', $pickup['pickup_address1']);
        $this->assertEquals('Manchester', $pickup['pickup_city']);
        $this->assertEquals('M1 1AA', $pickup['pickup_postcode']);
    }
}