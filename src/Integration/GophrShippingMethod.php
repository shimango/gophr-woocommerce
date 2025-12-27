<?php
namespace Gophr\Woocommerce\Integration;

use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;
use WC_Product_Simple;
use WC_Shipping_Method;

class GophrShippingMethod extends WC_Shipping_Method
{
    private Client $gophrClient;

    /**
     * Constructor.
     */
    public function __construct($instance_id = 0)
    {
        parent::__construct($instance_id);
        $this->init_settings();

        $apiKey = get_option('gophr_api_key');
        $isSandbox = get_option('gophr_environment', false) === 'sandbox';

        $config = new Configuration($apiKey, $isSandbox);
        $this->gophrClient = new Client($config);
    }

    public function init_settings(): void
    {
        $methodTitle = get_option('gophr_shipping_title', 'Gophr Same-Day Delivery');
        $this->id = \Constants::$GOPHR_SAME_DAY_METHOD_ID;

        $this->title = __($methodTitle, 'gophr-same-day');
        $this->method_title = __($methodTitle, 'gophr-same-day');
        $this->method_description = __('Custom shipping via Gophr API', 'gophr-same-day');
        $this->supports = ['shipping-zones', 'instance-settings'];
        $this->enabled = get_option('gophr_enable', 'yes');

        add_action('woocommerce_order_status_processing', [$this, 'create_delivery_job'], 10, 1); // Or use 'woocommerce_checkout_order_processed' for immediate creation.
        add_action('woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options']);

        parent::init_settings();
    }

    public static function getInstance(): GophrShippingMethod
    {
        return new self();
    }

    /**
     * Calculate shipping rate via your API.
     */
    public function calculate_shipping($package = []) {
        $parcels = $this->getGophrParcelsPayload($package);
        $payload = $this->getGophrRequestPayload($package, $parcels);

        $quote = $this->gophrClient->getQuote($payload)->getContentsObject();

        if ($quote !== null) {
            // Add the rate to checkout.
            $this->add_rate([
                'id' => $this->id . '_' . $this->instance_id,
                'label' => $this->title,
                'cost' => $quote->data->price_net->amount,
                'package' => $package,
            ]);
        }
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

    private function getGophrRequestPayload(array $order, array $parcels): array
    {
        $origin = [
            "pickup_address1" => get_option('woocommerce_store_address'),
            "pickup_address2" => get_option('woocommerce_store_address_2'),
            "pickup_city" => get_option('woocommerce_store_city'),
            "pickup_postcode" => get_option('woocommerce_store_postcode'),
            "pickup_country_code" => substr( get_option( 'woocommerce_default_country' ), 0, 2 ), // GB
        ];

        $destination = $order['destination'];

        return [
            "pickups" => $this->getGophrPickupPayload($origin, $parcels),
            "dropoffs" => $this->getGophrDropoffPayload($destination, $parcels),
            "meta_data" => [["booking_method" => "gophr-woocommerce"],
            ]
        ];
    }

    private function getGophrPickupPayload(array $origin, array $parcels): array
    {
        $pickup = [
//            "earliest_pickup_time" => (new \DateTime('tomorrow noon'))->format(DateTimeInterface::ATOM),
//            "pickup_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT2H'))->format(DateTimeInterface::ATOM),
            "pickup_address1" => $origin['pickup_address1'],
            "pickup_address2" => $origin['pickup_address2'],
            "pickup_city" => $origin['pickup_city'],
            "pickup_postcode" => $origin['pickup_postcode'],
            "pickup_country_code" => $origin['pickup_country_code'],
//            "pickup_location_lat" => "51.4997819",
//            "pickup_location_lng" => "-0.0784133",
//            "pickup_company_name" => "Gophr Ltd",
//            "pickup_person_name" => "John Smith",
//            "pickup_email" => "john.smith@gophr.com",
//            "pickup_mobile_number" => "07711111112",
//            "pickup_phone_number" => "07722222232",
//            "pickup_proof_required" => 1,
//            "is_first_pickup" => 0,
//            "sequence_number" => 1,
            "parcels" => $parcels,
        ];

        return [array_filter($pickup)];
    }

    private function getGophrDropoffPayload(array $destination, array $parcels): array
    {
        $dropoff = [
//            "min_required_age" => 0,
//            "dropoff_company_name" => "Private Investigators",
            "dropoff_address1" => $destination['address_1'],
            "dropoff_address2" => $destination['address_2'],
            "dropoff_city" => $destination['city'],
            "dropoff_postcode" => $destination['postcode'],
            "dropoff_country_code" => $destination['country'],
//            "dropoff_location_lat" => "51.49" . rand(100000, 999999),
//            "dropoff_location_lng" => "-0.17" . rand(100000, 999999),
//            "dropoff_tips_how_to_find" => "It's elementary my dear Watson",
//            "dropoff_person_name" => "Sherlock Holmes",
//            "dropoff_email" => "sherlok.holmer@gohr.com",
//            "dropoff_mobile_number" => "07766663666",
//            "dropoff_phone_number" => "07735555555",
//            "earliest_dropoff_time" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT2H'))->format(DateTimeInterface::ATOM),
//            "dropoff_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT4H'))->format(DateTimeInterface::ATOM),
//            "dropoff_proof_required" => 0,
//            "cold_chain" => 0,
//            "is_final_dropoff" => 0,
//            "sequence_number" => 2,
//            "leg_type" => "STANDARD",
            "parcels" => $parcels,
        ];

        return [array_filter($dropoff)];
    }

    private function getGophrParcelsPayload(array $package): array
    {
//        $total_weight = 0;
//        $parcel = ['length' => 0, 'width' => 0, 'height' => 0, 'weight' => 0];

        $parcels = [];

        foreach ($package['contents'] as $itemId => $item) {
            /** @var WC_Product_Simple $product */
            $product = $item['data'];
            $quantity = (float) $item['quantity'];

            $parcelExternalId = sprintf('Product id #%s', $product->get_id() ?: $itemId);
            $length = $quantity * (float) $product->get_length();
            $width = $quantity * (float) $product->get_width();
            $height = $quantity * (float) $product->get_height();
            $weight = $quantity * (float) $product->get_weight();

            $parcels[] = [
                "parcel_external_id" => $parcelExternalId,
//                "parcel_reference_number" => "7fc35278-60a1-4fb3-9791-4f45c492e120",
//                "parcel_description" => "Big2!",
//                "parcel_insurance_value" => 150,
//                "id_check" => 0,
                "width" => $width,
                "length" => $length,
                "height" => $height,
                "weight" => $weight,
//                "is_food" => 0,
//                "is_fragile" => 0,
//                "is_liquid" => 0,
//                "is_not_rotatable" => 0,
//                "is_glass" => 0,
//                "is_baked" => 0,
//                "is_flower" => 0,
//                "is_alcohol" => 0,
//                "is_beef" => 0,
//                "is_pork" => 0
            ];
        }

        return array_filter($parcels);
    }
}
