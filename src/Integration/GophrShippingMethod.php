<?php

namespace Gophr\Woocommerce\Integration;

use Gophr\Woocommerce\Utils\Payload;
use Shimango\Gophr\Client;
use Shimango\Gophr\Common\Configuration;
use WC_Logger_Interface;
use WC_Shipping_Method;

class GophrShippingMethod extends WC_Shipping_Method {

    private Client $gophrClient;
    private WC_Logger_Interface $logger;

    public function __construct($instance_id = 0) {
        parent::__construct($instance_id);

        $this->id = GOPHR_METHOD_ID;
        $this->init_settings();
        $this->init_gophr_client();

        // Register hooks in constructor, not init_settings
        add_action('woocommerce_order_status_processing', [$this, 'create_delivery_job'], 10, 1);

        $this->logger = wc_get_logger();
    }

    private function init_gophr_client(): void {
        $apiKey = sanitize_text_field(get_option('gophr_api_key', ''));
        $isSandbox = get_option('gophr_environment', 'production') === 'sandbox';

        if (empty($apiKey)) {
            $this->logger->warning('Gophr API key not configured', [
                'source' => 'gophr-same-day',
            ]);
            return;
        }

        try {
            $config = new Configuration($apiKey, $isSandbox);
            $this->gophrClient = new Client($config);
        } catch (\Exception $e) {
            $this->logger->error('Failed to initialize Gophr client: ' . $e->getMessage(), [
                'source' => 'gophr-same-day',
            ]);
        }
    }

    public function init_settings(): void {
        $methodTitle = sanitize_text_field(
            get_option('gophr_shipping_title', 'Gophr Same-Day Delivery')
        );

        $this->title = __($methodTitle, 'gophr-same-day');
        $this->method_title = __($methodTitle, 'gophr-same-day');
        $this->method_description = __('Same-day delivery powered by Gophr', 'gophr-same-day');
        $this->supports = ['shipping-zones', 'instance-settings'];
        $this->enabled = get_option('gophr_enable', 'yes');

        parent::init_settings();
    }

    /**
     * Calculate shipping rate via Gophr API.
     *
     * @param array $package Shipping package data.
     * @return bool Success status.
     */
    public function calculate_shipping($package = []): bool {
        if (!isset($this->gophrClient)) {
            $this->logger->error('Gophr client not initialized', [
                'source' => 'gophr-same-day',
            ]);
            return false;
        }

        // Validate package
        if (empty($package['destination']) || empty($package['contents'])) {
            return false;
        }

        $package['external_id'] = sprintf('quote-%s-%s', GOPHR_PLUGIN_NAME, time());

        try {
            $payload = Payload::getRequestPayload($package);
            $response = $this->gophrClient->getQuote(array_filter($payload->toArray()));

            if ($response->getStatusCode() !== 200) {
                $this->log_api_error('Quote failed', $response);
                return false;
            }

            $contents = $response->getContentsArray();

            // Validate response structure
            if (!isset($contents['data']['price_net']['amount'])) {
                $this->logger->error('Invalid API response structure', [
                    'source' => 'gophr-same-day',
                    'response' => $contents,
                ]);
                return false;
            }

            $price = floatval($contents['data']['price_net']['amount']);

            if ($price <= 0) {
                $this->logger->warning('Invalid price returned from API', [
                    'source' => 'gophr-same-day',
                    'price' => $price,
                ]);
                return false;
            }

            $this->add_rate([
                'id' => $this->id . '_' . $this->instance_id,
                'label' => $this->title,
                'cost' => $price,
                'package' => $package,
                'meta_data' => [
                    'gophr_quote_id' => $contents['data']['quote_id'] ?? '',
                ],
            ]);

            // Store parcels - check if session is available
            $parcels = $payload->pickups[0]->parcels;
            if (WC()->session) {
                WC()->session->set("{$this->id}_shipping_parcels", $parcels);
            } else {
                // Fallback to transient if session not available
                set_transient(
                    "gophr_parcels_temp_" . md5(serialize($package)),
                    $parcels,
                    HOUR_IN_SECONDS
                );
            }

            return true;

        } catch (\Exception $e) {
            $this->logger->error('API Error: ' . $e->getMessage(), [
                'source' => 'gophr-same-day',
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Create delivery job when order is processed.
     *
     * @param int $order_id Order ID.
     * @return bool Success status.
     */
    public function create_delivery_job($order_id): bool {
        if (!isset($this->gophrClient)) {
            return false;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            $this->logger->error('Order not found', [
                'source' => 'gophr-same-day',
                'order_id' => $order_id,
            ]);
            return false;
        }

        // Check if Gophr shipping was selected
        $shipping_methods = $order->get_shipping_methods();
        if (empty($shipping_methods)) {
            return false;
        }

        $shipping_method = reset($shipping_methods);
        if (strpos($shipping_method->get_method_id(), $this->id) === false) {
            return false;
        }

        // Check if job already created
        $existing_job_id = $order->get_meta("{$this->id}_delivery_job_id", true);
        if (!empty($existing_job_id)) {
            $this->logger->info('Delivery job already exists', [
                'source' => 'gophr-same-day',
                'order_id' => $order_id,
                'job_id' => $existing_job_id,
            ]);
            return false;
        }

        try {
            $billing = array_filter($order->get_address('billing'));
            $shipping = array_filter($order->get_address('shipping'));

            $package['destination'] = array_merge($billing, $shipping);
            $package['external_id'] = (string) $order_id;

            // Retrieve parcels
            $parcels = WC()->session ?
                WC()->session->get("{$this->id}_shipping_parcels") :
                get_transient("gophr_parcels_temp_" . md5(serialize($package)));

            $payload = Payload::getRequestPayload($package, $parcels);
            $response = $this->gophrClient->createJob($payload->toArray());

            if ($response->getStatusCode() !== 201) {
                $this->log_api_error('Job creation failed', $response);
                $order->add_order_note(
                    __('Failed to create Gophr delivery job. Please check logs.', 'gophr-same-day')
                );
                return false;
            }

            $responseObj = $response->getContentsObject();
            $job_id = $responseObj->data->job_id ?? null;

            if (empty($job_id)) {
                $this->logger->error('No job ID in response', [
                    'source' => 'gophr-same-day',
                    'response' => $response->getContentsArray(),
                ]);
                return false;
            }

            // Update order with job ID
            $order->update_meta_data("{$this->id}_delivery_job_id", sanitize_text_field($job_id));
            $order->add_order_note(
                sprintf(
                    __('Gophr delivery job created successfully. Job ID: %s', 'gophr-same-day'),
                    $job_id
                )
            );
            $order->save();

            $this->logger->info('Delivery job created', [
                'source' => 'gophr-same-day',
                'order_id' => $order_id,
                'job_id' => $job_id,
            ]);

            return true;

        } catch (\Exception $e) {
            $this->logger->error('Failed to create delivery job: ' . $e->getMessage(), [
                'source' => 'gophr-same-day',
                'order_id' => $order_id,
                'trace' => $e->getTraceAsString(),
            ]);

            $order->add_order_note(
                __('Error creating Gophr delivery job. Please contact support.', 'gophr-same-day')
            );

            return false;
        }
    }

    /**
     * Log API errors consistently.
     *
     * @param string $message Error message.
     * @param mixed $response API response.
     */
    private function log_api_error(string $message, $response): void {
        $this->logger->error($message, [
            'source' => 'gophr-same-day',
            'status_code' => $response->getStatusCode(),
            'errors' => $response->getContentsArray()['errors'] ?? [],
        ]);
    }
}
