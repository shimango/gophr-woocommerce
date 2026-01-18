<?php

namespace Gophr\Tests\Unit;

use Gophr\Tests\TestCase;
use Gophr\Woocommerce\Integration\GophrShippingMethod;

class GophrShippingMethodTest extends TestCase
{

    private GophrShippingMethod $shipping_method;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shipping_method = new GophrShippingMethod();
    }

    public function test_shipping_method_has_correct_id()
    {
        $this->assertEquals('gophr-same-day', $this->shipping_method->id);
    }

    public function test_shipping_method_title_is_set()
    {
        $this->assertNotEmpty($this->shipping_method->title);
        $this->assertEquals('Gophr Same-Day Delivery', $this->shipping_method->title);
    }

    public function test_shipping_method_is_enabled_by_default()
    {
        $this->assertEquals('yes', $this->shipping_method->enabled);
    }

    public function test_shipping_method_supports_shipping_zones()
    {
        $this->assertContains('shipping-zones', $this->shipping_method->supports);
    }

    public function test_shipping_method_supports_instance_settings()
    {
        $this->assertContains('instance-settings', $this->shipping_method->supports);
    }

    public function test_method_description_is_set()
    {
        $this->assertNotEmpty($this->shipping_method->method_description);
    }

    public function test_get_instance_returns_gophr_shipping_method()
    {
        $instance = GophrShippingMethod::getInstance();
        $this->assertInstanceOf(GophrShippingMethod::class, $instance);
    }
}