<?php
namespace Gophr\Woocommerce\Integration;

use Constants;
use Gophr\Woocommerce\Utils\Payload;
use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;
use WC_Logger_Interface;
use WC_Shipping_Method;

class GophrShippingMethod extends WC_Shipping_Method
{
    private Client $gophrClient;
    private WC_Logger_Interface $logger;

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

        $this->logger = wc_get_logger();
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

        add_action('woocommerce_order_status_processing', [$this, 'create_delivery_job'], 10, 1);
        parent::init_settings();
    }

    public static function getInstance(): GophrShippingMethod
    {
        return new self();
    }

    /**
     * Calculate shipping rate via your API.
     */
    public function calculate_shipping($package = []): bool
    {
        $package['external_id'] = sprintf('quote-%s', Constants::$GOPHR_SAME_DAY_PLUGIN);
        $payload = Payload::getRequestPayload($package);

        $response = $this->gophrClient->getQuote(array_filter($payload->toArray()));

        if ($response->getStatusCode() !== 200) {
            $this->logger->log('error', 'Payload', [
                'source' => 'gophr-same-day',
                'data' => $response->getContentsArray()['errors'],
            ]);

            return false;
        }

        $responseObj = $response->getContentsObject();
        $price = $responseObj?->data?->price_net?->amount ?? $response->getContentsArray()['data']['price_net']['amount'];

        if ($price) {
            $this->add_rate([
                'id' => $this->id . '_' . $this->instance_id,
                'label' => $this->title,
                'cost' => $price,
                'package' => $package,
            ]);

            $parcels = $payload->pickups[0]->parcels;
            WC()->session->set("{$this->id}gophr_shipping_parcels", $parcels);
        } else  {
             $this->logger->log('error', 'Payload', [
                'source' => 'gophr-same-day',
                'data' => $payload,
            ]);

             return false;
        }

        return true;
    }

    /**
     * Create delivery job via API when order is processed.
     */
    public function create_delivery_job($order_id): bool
    {
        $order = wc_get_order($order_id);

        if (!$order) {
            return false;
        }

        // Check if the Gophr shipping method was selected.
        $shipping_methods = $order->get_shipping_methods();
        $shipping_method_id = reset($shipping_methods)['method_id'];
        if (strpos($shipping_method_id, $this->id) === false) {
            return false;
        }

        $billing = array_filter($order->get_address('billing'));
        $shipping = array_filter($order->get_address('shipping'));

        $package['destination'] = array_merge($billing, $shipping);
        $package['external_id'] = "{$order_id}";

        $parcels = WC()->session->get("{$this->id}gophr_shipping_parcels");
        $payload = Payload::getRequestPayload($package, $parcels);

        $response = $this->gophrClient->createJob($payload->toArray());

        if ($response->getStatusCode() !== 201) {
            $this->logger->log('error', 'Payload', [
                'source' => 'gophr-same-day',
                'data' => $response->getContentsArray()['errors'],
            ]);

            return false;
        }

        $responseObj = $response->getContentsObject();

        $job_id = $responseObj->data->job_id;

        // Update order with success note and job ID.
        $order->update_meta_data("{$this->id}_delivery_job_id", $job_id);
        $order->add_order_note('Delivery job created successfully. Job ID: ' . $job_id);
        $order->save();

        return true;
    }
}
