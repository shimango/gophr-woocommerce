<?php

namespace Gophr\Tests;

use WP_UnitTestCase;
use WC_Product_Simple;
use WC_Order;

abstract class TestCase extends WP_UnitTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        if (!class_exists('WooCommerce')) {
            $this->markTestSkipped('WooCommerce is not installed.');
        }

        // Set up WooCommerce
        update_option('woocommerce_store_address', '123 Test Street');
        update_option('woocommerce_store_address_2', 'Suite 100');
        update_option('woocommerce_store_city', 'London');
        update_option('woocommerce_store_postcode', 'SW1A 1AA');
        update_option('woocommerce_default_country', 'GB:ENG');

        // Set up Gophr settings
        update_option('gophr_api_key', 'test_api_key_12345');
        update_option('gophr_environment', 'sandbox');
        update_option('gophr_enable', 'yes');
        update_option('gophr_shipping_title', 'Gophr Same-Day Delivery');

        // Set up Gophr origin settings
        update_option('gophr_user_name', 'Test User');
        update_option('gophr_origin_company_name', 'Test Company');
        update_option('gophr_origin_address1', '123 Test Street');
        update_option('gophr_origin_address2', 'Suite 100');
        update_option('gophr_origin_city', 'London');
        update_option('gophr_origin_postcode', 'SW1A 1AA');
        update_option('gophr_origin_country', 'GB');
        update_option('gophr_phone_number', '07588112233');
        update_option('gophr_email_address', 'test@example.com');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up
        delete_option('gophr_api_key');
        delete_option('gophr_environment');
        delete_option('gophr_enable');
        delete_option('gophr_shipping_title');

        // Clean up origin settings
        delete_option('gophr_user_name');
        delete_option('gophr_origin_company_name');
        delete_option('gophr_origin_address1');
        delete_option('gophr_origin_address2');
        delete_option('gophr_origin_city');
        delete_option('gophr_origin_postcode');
        delete_option('gophr_origin_country');
        delete_option('gophr_phone_number');
        delete_option('gophr_email_address');

        // Clean up delivery options
        delete_option('gophr_vehicle_type');
        delete_option('gophr_pickup_proof_required');
        delete_option('gophr_dropoff_proof_required');
        delete_option('gophr_min_required_age');
        delete_option('gophr_pin_required');
        delete_option('gophr_id_check');
        delete_option('gophr_pickup_instructions');
        delete_option('gophr_pickup_tips');
        delete_option('gophr_parcel_flags');
        delete_option('gophr_cold_chain');
    }

    protected function create_test_product(array $args = []): WC_Product_Simple
    {
        $defaults = [
            'name' => 'Test Product',
            'regular_price' => '10.00',
            'weight' => '1',
            'length' => '10',
            'width' => '10',
            'height' => '10',
        ];

        $args = wp_parse_args($args, $defaults);

        $product = new WC_Product_Simple();
        $product->set_props($args);
        $product->save();

        return $product;
    }

    protected function create_test_order(array $customer_data = []): WC_Order
    {
        $order = wc_create_order();

        $defaults = [
            'billing_first_name' => 'John',
            'billing_last_name' => 'Doe',
            'billing_address_1' => '123 Main St',
            'billing_city' => 'London',
            'billing_postcode' => 'SW1A 1AA',
            'billing_country' => 'GB',
            'billing_phone' => '07588112233',
            'shipping_first_name' => 'John',
            'shipping_last_name' => 'Doe',
            'shipping_address_1' => '456 High St',
            'shipping_city' => 'London',
            'shipping_postcode' => 'E1 6AN',
            'shipping_country' => 'GB',
            'shipping_phone' => '07588112233',
        ];

        $customer_data = wp_parse_args($customer_data, $defaults);

        foreach ($customer_data as $key => $value) {
            $order->{"set_$key"}($value);
        }

        $order->save();

        return $order;
    }

    protected function create_shipping_package(array $products = []): array
    {
        if (empty($products)) {
            $products = [$this->create_test_product()];
        }

        $contents = [];
        foreach ($products as $product) {
            $contents[] = [
                'key' => 'item_' . $product->get_id(),
                'product_id' => $product->get_id(),
                'quantity' => 1,
                'data' => $product,
            ];
        }

        return [
            'contents' => $contents,
            'destination' => [
                'address_1' => '456 High St',
                'address_2' => '',
                'city' => 'London',
                'postcode' => 'E1 6AN',
                'country' => 'GB',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '07588112233',
            ],
        ];
    }
}