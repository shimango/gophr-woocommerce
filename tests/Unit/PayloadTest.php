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

    public function test_parcel_creates_individual_parcels_per_quantity()
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

        // Should create 3 individual parcels, not 1 with multiplied dimensions
        $this->assertCount(3, $parcels);

        // Each parcel should have the original product dimensions
        foreach ($parcels as $parcel) {
            $this->assertEquals(10.0, $parcel->length);
            $this->assertEquals(10.0, $parcel->width);
            $this->assertEquals(10.0, $parcel->height);
            $this->assertEquals(1.0, $parcel->weight);
        }

        // Check that each parcel has a unique external ID
        $external_ids = array_map(fn($p) => $p->parcel_external_id, $parcels);
        $this->assertCount(3, array_unique($external_ids));
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

    public function test_payload_uses_gophr_settings_for_pickup()
    {
        update_option('gophr_origin_address1', '456 Gophr Street');
        update_option('gophr_origin_city', 'Birmingham');
        update_option('gophr_origin_postcode', 'B1 1AA');
        update_option('gophr_origin_company_name', 'Gophr Test Company');
        update_option('gophr_user_name', 'Gophr Test User');
        update_option('gophr_phone_number', '07700900000');

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $pickup = $array['pickups'][0];
        $this->assertEquals('456 Gophr Street', $pickup['pickup_address1']);
        $this->assertEquals('Birmingham', $pickup['pickup_city']);
        $this->assertEquals('B1 1AA', $pickup['pickup_postcode']);
        $this->assertEquals('Gophr Test Company', $pickup['pickup_company_name']);
        $this->assertEquals('Gophr Test User', $pickup['pickup_person_name']);
        $this->assertEquals('07700900000', $pickup['pickup_phone_number']);
    }

    public function test_payload_falls_back_to_woocommerce_settings()
    {
        // Clear Gophr-specific settings
        delete_option('gophr_origin_address1');
        delete_option('gophr_origin_city');
        delete_option('gophr_origin_postcode');

        // Set WooCommerce store settings
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

    public function test_payload_includes_vehicle_type_when_set()
    {
        update_option('gophr_vehicle_type', '30'); // Car

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $this->assertArrayHasKey('vehicle_type', $array);
        $this->assertEquals(30, $array['vehicle_type']);
    }

    public function test_payload_excludes_vehicle_type_when_empty()
    {
        update_option('gophr_vehicle_type', '');

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $this->assertArrayNotHasKey('vehicle_type', $array);
    }

    public function test_payload_includes_pickup_proof_when_set()
    {
        update_option('gophr_pickup_proof_required', '2'); // Signature

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $pickup = $array['pickups'][0];
        $this->assertArrayHasKey('pickup_proof_required', $pickup);
        $this->assertEquals(2, $pickup['pickup_proof_required']);
    }

    public function test_payload_includes_pickup_instructions_when_set()
    {
        update_option('gophr_pickup_instructions', 'Ring bell twice');
        update_option('gophr_pickup_tips', 'Blue door on the left');

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $pickup = $array['pickups'][0];
        $this->assertEquals('Ring bell twice', $pickup['pickup_instructions']);
        $this->assertEquals('Blue door on the left', $pickup['pickup_tips_how_to_find']);
    }

    public function test_payload_includes_dropoff_proof_when_set()
    {
        update_option('gophr_dropoff_proof_required', '3'); // Photo & Signature

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $dropoff = $array['dropoffs'][0];
        $this->assertArrayHasKey('dropoff_proof_required', $dropoff);
        $this->assertEquals(3, $dropoff['dropoff_proof_required']);
    }

    public function test_payload_includes_age_verification_when_set()
    {
        update_option('gophr_min_required_age', '18');

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $dropoff = $array['dropoffs'][0];
        $this->assertArrayHasKey('min_required_age', $dropoff);
        $this->assertEquals(18, $dropoff['min_required_age']);
    }

    public function test_payload_includes_pin_required_when_enabled()
    {
        update_option('gophr_pin_required', 'yes');

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $dropoff = $array['dropoffs'][0];
        $this->assertArrayHasKey('pin_required', $dropoff);
        $this->assertEquals(1, $dropoff['pin_required']);
    }

    public function test_payload_includes_cold_chain_when_set()
    {
        update_option('gophr_cold_chain', '3600'); // 1 hour

        $package = $this->create_shipping_package();
        $package['external_id'] = 'test';

        $payload = Payload::getRequestPayload($package);
        $array = $payload->toArray();

        $dropoff = $array['dropoffs'][0];
        $this->assertArrayHasKey('cold_chain', $dropoff);
        $this->assertEquals(3600, $dropoff['cold_chain']);
    }

    public function test_parcel_includes_flags_when_set()
    {
        update_option('gophr_parcel_flags', [
            'is_food' => 'yes',
            'is_fragile' => 'yes',
        ]);

        $product = $this->create_test_product();
        $package = $this->create_shipping_package([$product]);

        $parcels = Payload::getParcelsPayload($package);
        $parcel = $parcels[0];

        $this->assertEquals(1, $parcel->is_food);
        $this->assertEquals(1, $parcel->is_fragile);
    }

    public function test_parcel_includes_id_check_when_enabled()
    {
        update_option('gophr_id_check', 'yes');

        $product = $this->create_test_product();
        $package = $this->create_shipping_package([$product]);

        $parcels = Payload::getParcelsPayload($package);
        $parcel = $parcels[0];

        $this->assertEquals(1, $parcel->id_check);
    }
}