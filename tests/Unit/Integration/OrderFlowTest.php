<?php

namespace Gophr\Tests\Integration;

use Gophr\Tests\TestCase;
use Gophr\Woocommerce\Integration\GophrShippingMethod;
use WC_Order_Item_Shipping;

class OrderFlowTest extends TestCase
{

    public function test_complete_order_creation_flow()
    {
        $product = $this->create_test_product([
            'regular_price' => '25.00',
            'weight' => '2',
        ]);

        $order = $this->create_test_order();
        $order->add_product($product, 1);

        // Add Gophr shipping
        $shipping_item = new WC_Order_Item_Shipping();
        $shipping_item->set_method_title('Gophr Same-Day Delivery');
        $shipping_item->set_method_id('gophr-same-day');
        $shipping_item->set_total(5.99);

        $order->add_item($shipping_item);
        $order->calculate_totals();
        $order->save();

        $this->assertGreaterThan(0, $order->get_id());
        $this->assertEquals('25.00', $order->get_subtotal());
    }

    public function test_order_meta_can_be_saved()
    {
        $order = $this->create_test_order();

        update_post_meta($order->get_id(), 'gophr-same-day_delivery_job_id', 'job_12345');

        $saved_job_id = get_post_meta($order->get_id(), 'gophr-same-day_delivery_job_id', true);
        $this->assertEquals('job_12345', $saved_job_id);
    }

    public function test_order_has_gophr_shipping_method()
    {
        $order = $this->create_test_order();

        $shipping_item = new WC_Order_Item_Shipping();
        $shipping_item->set_method_title('Gophr Same-Day Delivery');
        $shipping_item->set_method_id('gophr-same-day');
        $shipping_item->set_total(7.50);

        $order->add_item($shipping_item);
        $order->save();

        $shipping_methods = $order->get_shipping_methods();
        $this->assertNotEmpty($shipping_methods);

        $method = reset($shipping_methods);
        $this->assertEquals('gophr-same-day', $method->get_method_id());
    }
}