<?php
namespace GophrSameDay\Core;

use WC_Shipping_Method;

class Shipping extends WC_Shipping_Method {

    /**
     * Constructor.
     */
    public function __construct($instance_id = 0) {
        $this->id = 'your_delivery';
        $this->instance_id = absint($instance_id);
        $this->method_title = __('Gophr Same-Day Delivery', 'gophr-same-day');
        $this->method_description = __('Custom shipping via Gophr API', 'gophr-same-day');
        $this->supports = array('shipping-zones', 'instance-settings');
        $this->init();

        add_action('woocommerce_order_status_processing', array($this, 'create_delivery_job'), 10, 1); // Or use 'woocommerce_checkout_order_processed' for immediate creation.

        parent::__construct($instance_id);
    }

    /**
     * Initialize settings and form fields.
     */
    public function init() {
        $this->init_form_fields();
        $this->init_settings();

        // Define settings (accessible in WooCommerce > Settings > Shipping > Your Delivery).
        $this->enabled = $this->get_option('enabled', 'yes');
        $this->title = $this->get_option('title', __('Gophr Same-Day Delivery', 'gophr-same-day'));
        $this->api_endpoint_quote = $this->get_option('api_endpoint_quote', 'https://yourapi.com/quote'); // Your quote endpoint.
        $this->api_endpoint_create = $this->get_option('api_endpoint_create', 'https://yourapi.com/create-job'); // Your create job endpoint.
        $this->api_key = $this->get_option('api_key', ''); // API key for authentication.

        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }

    /**
     * Admin settings fields.
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'gophr-same-day'),
                'type' => 'checkbox',
                'label' => __('Enable Gophr Same-Day Delivery', 'gopphr-same-day'),
                'default' => 'yes',
            ),
            'title' => array(
                'title' => __('Method Title', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Title shown in checkout', 'gopphr-same-day'),
                'default' => __('Gophr Same-Day Delivery', 'gopphr-same-day'),
                'desc_tip' => true,
            ),
            'api_endpoint_quote' => array(
                'title' => __('Quote API Endpoint', 'gopphr-same-day'),
                'type' => 'text',
                'description' => __('URL for getting delivery quotes', 'gopphr-same-day'),
                'default' => 'https://yourapi.com/quote',
                'desc_tip' => true,
            ),
            'api_endpoint_create' => array(
                'title' => __('Create Job API Endpoint', 'gopphr-same-day'),
                'type' => 'text',
                'description' => __('URL for creating delivery jobs', 'gopphr-same-day'),
                'default' => 'https://yourapi.com/create-job',
                'desc_tip' => true,
            ),
            'api_key' => array(
                'title' => __('API Key', 'gopphr-same-day'),
                'type' => 'password',
                'description' => __('Your API key for authentication', 'gopphr-same-day'),
                'default' => '',
                'desc_tip' => true,
            ),
        );
    }

    /**
     * Calculate shipping rate via your API.
     */
    public function calculate_shipping($package = array()) {
        // // Gather data for quote: dimensions, weight, distance (e.g., from shop to customer address).
         $destination = $package['destination'];
         $total_weight = 0;
         $dimensions = array(); // Collect per item or aggregate as needed.

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
         $shop_address = get_option('woocommerce_store_address'); // Customize as needed.
         $distance = $this->calculate_distance($shop_address, $destination); // Implement this function if needed.

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
        $this->add_rate(array(
            'id' => $this->id . '_' . $this->instance_id,
            'label' => $this->title,
            'cost' => 50.05, //$cost,
            'package' => $package,
        ));
    }

    // Optional: Implement distance calculation (e.g., using a geolocation API).
    private function calculate_distance($from, $to) {
        // Use external API or library here (e.g., Google Distance Matrix API).
        return 0; // Placeholder.
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
