<?php

namespace Gophr\Tests\Unit;

use Gophr\Tests\TestCase;
use Gophr\Woocommerce\Plugin\GophrPlugin;

class GophrPluginTest extends TestCase
{

    public function test_get_instance_returns_singleton()
    {
        $instance1 = GophrPlugin::getInstance();
        $instance2 = GophrPlugin::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function test_instance_is_gophr_plugin()
    {
        $instance = GophrPlugin::getInstance();
        $this->assertInstanceOf(GophrPlugin::class, $instance);
    }

    public function test_shipping_method_is_registered()
    {
        $methods = apply_filters('woocommerce_shipping_methods', []);

        $this->assertArrayHasKey('gophr-same-day', $methods);
        $this->assertEquals(
            \Gophr\Woocommerce\Integration\GophrShippingMethod::class,
            $methods['gophr-same-day']
        );
    }

    public function test_text_domain_is_loaded()
    {
        $this->assertTrue(is_textdomain_loaded('gophr-same-day'));
    }
}