<?php
namespace Gophr\Woocommerce\Integration;

use WC_Shipping_Method;

class GophrShippingMethod extends WC_Shipping_Method
{
    /**
     * Constructor.
     */
    public function __construct($instance_id = 0)
    {
        $methodTitle = get_option('shipping_title', 'Gophr Same-Day Delivery');
        $this->id = \Constants::$GOPHR_SAME_DAY_METHOD_ID;

        $this->instance_id = absint($instance_id);
        $this->method_title = __($methodTitle, 'gophr-same-day');
        $this->method_description = __('Custom shipping via Gophr API', 'gophr-same-day');
        $this->supports = ['shipping-zones', 'instance-settings'];

        add_action('woocommerce_order_status_processing', [$this, 'create_delivery_job'], 10, 1); // Or use 'woocommerce_checkout_order_processed' for immediate creation.
        add_action('woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options']);

//        parent::__construct($instance_id);

        $this->init_settings();
    }

    public static function getInstance(): GophrShippingMethod
    {
        return new self();
    }

    /**
     * Calculate shipping rate via your API.
     */
    public function calculate_shipping($package = []) {
        // // Gather data for quote: dimensions, weight, distance (e.g., from shop to customer address).
         $destination = $package['destination'];
         $total_weight = 0;
         $dimensions = []; // Collect per item or aggregate as needed.

//         foreach ($package['contents'] as $item) {
//             $product = $item['data'];
//             $total_weight += $product->get_weight() * $item['quantity'];
//             // Add dimensions if your API needs them (e.g., length, width, height per parcel).
//             $dimensions[] = array(
//                 'length' => $product->get_length(),
//                 'width' => $product->get_width(),
//                 'height' => $product->get_height(),
//             );
//         }

         // Calculate distance (you may need a separate API or library like Google Maps for accuracy; for now, assume postcode-based).
//         $shop_address = get_option('woocommerce_store_address'); // Customize as needed.
//         $distance = $this->calculate_distance($shop_address, $destination); // Implement this function if needed.

        $address  = get_option( 'woocommerce_store_address' );
        $address2 = get_option( 'woocommerce_store_address_2' );
        $city     = get_option( 'woocommerce_store_city' );
        $postcode = get_option( 'woocommerce_store_postcode' );
        $country  = get_option( 'woocommerce_default_country' );


        // // Prepare API request body (adjust to your API's requirements).
        // $request_body = array(
        //     'parcels' => $dimensions, // Or aggregate if single parcel.
        //     'weight' => $total_weight,
        //     'distance' => $distance,
        //     'from' => $shop_address,
        //     'to' => $destination,
        // );

        // // Make API call.
        // $response = wp_remote_post($this->api_endpoint_quote, array(
        //     'headers' => array(
        //         'Authorization' => 'Bearer ' . $this->api_key,
        //         'Content-Type' => 'application/json',
        //     ),
        //     'body' => json_encode($request_body),
        // ));

        // if (is_wp_error($response)) {
        //     // Handle error (e.g., log it, fallback to flat rate).
        //     return;
        // }

        // $body = json_decode(wp_remote_retrieve_body($response), true);
        // $cost = isset($body['quote']) ? $body['quote'] : 0; // Adjust based on your API response.

        // Add the rate to checkout.
        $this->add_rate([
            'id' => $this->id . '_' . $this->instance_id,
            'label' => $this->title,
            'cost' => 50.16, //$cost,
            'package' => $package,
        ]);
    }

    /**
     * Create delivery job via API when order is processed.
     */
    public function create_delivery_job($order_id) {
         $order = wc_get_order($order_id);
         if (!$order) return;

        // // Check if our shipping method was selected.
        // $shipping_methods = $order->get_shipping_methods();
        // $shipping_method_id = reset($shipping_methods)['method_id'];
        // if (strpos($shipping_method_id, $this->id) === false) return;

        // // Gather order details for your API.
        // $billing = $order->get_address('billing');
        // $shipping = $order->get_address('shipping');
        // $items = array();
        // foreach ($order->get_items() as $item) {
        //     $product = $item->get_product();
        //     $items[] = array(
        //         'name' => $item->get_name(),
        //         'quantity' => $item->get_quantity(),
        //         'weight' => $product->get_weight(),
        //         'dimensions' => array(
        //             'length' => $product->get_length(),
        //             'width' => $product->get_width(),
        //             'height' => $product->get_height(),
        //         ),
        //     );
        // }

        // // Prepare API request body (adjust to your create-job endpoint).
        // $request_body = array(
        //     'order_id' => $order_id,
        //     'pickup_address' => get_option('woocommerce_store_address'), // Shop address.
        //     'delivery_address' => $shipping,
        //     'customer_details' => $billing,
        //     'parcels' => $items,
        //     // Add weight, distance, etc., as needed.
        // );

        // // Make API call.
        // $response = wp_remote_post($this->api_endpoint_create, array(
        //     'headers' => array(
        //         'Authorization' => 'Bearer ' . $this->api_key,
        //         'Content-Type' => 'application/json',
        //     ),
        //     'body' => json_encode($request_body),
        // ));

        // if (is_wp_error($response)) {
        //     // Handle error (e.g., update order note).
        //     $order->add_order_note('Failed to create delivery job: ' . $response->get_error_message());
        //     return;
        // }

        // $body = json_decode(wp_remote_retrieve_body($response), true);
        // $job_id = isset($body['job_id']) ? $body['job_id'] : ''; // Store if needed.

        // // Update order with success note and job ID.
        // $order->update_meta_data('_your_delivery_job_id', $job_id);
        // $order->add_order_note('Delivery job created successfully. Job ID: ' . $job_id);
        // $order->save();
    }
}
