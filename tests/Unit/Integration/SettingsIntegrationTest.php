<?php

namespace Gophr\Tests\Integration;

use Gophr\Tests\TestCase;

class SettingsIntegrationTest extends TestCase
{

    public function test_api_key_can_be_saved_and_retrieved()
    {
        update_option('gophr_api_key', 'sk_test_12345');

        $retrieved = get_option('gophr_api_key');
        $this->assertEquals('sk_test_12345', $retrieved);
    }

    public function test_environment_can_be_changed()
    {
        update_option('gophr_environment', 'production');
        $this->assertEquals('production', get_option('gophr_environment'));

        update_option('gophr_environment', 'sandbox');
        $this->assertEquals('sandbox', get_option('gophr_environment'));
    }

    public function test_shipping_can_be_enabled_and_disabled()
    {
        update_option('gophr_enable', 'yes');
        $this->assertEquals('yes', get_option('gophr_enable'));

        update_option('gophr_enable', 'no');
        $this->assertEquals('no', get_option('gophr_enable'));
    }

    public function test_shipping_title_can_be_customized()
    {
        $custom_title = 'Express Same-Day Delivery';
        update_option('gophr_shipping_title', $custom_title);

        $this->assertEquals($custom_title, get_option('gophr_shipping_title'));
    }

    public function test_store_settings_exist()
    {
        $this->assertNotEmpty(get_option('woocommerce_store_address'));
        $this->assertNotEmpty(get_option('woocommerce_store_city'));
        $this->assertNotEmpty(get_option('woocommerce_store_postcode'));
    }
}