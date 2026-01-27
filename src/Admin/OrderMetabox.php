<?php

namespace Gophr\Woocommerce\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles the Gophr Shipment metabox on WooCommerce order pages.
 */
class OrderMetabox
{
    private static ?OrderMetabox $instance = null;

    public static function getInstance(): OrderMetabox
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addMetabox']);
        add_action('wp_ajax_gophr_edit_draft_job', [$this, 'handleEditDraftJob']);
        add_action('wp_ajax_gophr_accept_shipment', [$this, 'handleAcceptShipment']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    /**
     * Add the Gophr Shipment metabox to WooCommerce orders.
     */
    public function addMetabox(): void
    {
        $screen = $this->getOrderScreen();

        add_meta_box(
            'gophr_shipment_metabox',
            __('Gophr Shipment', 'gophr-same-day'),
            [$this, 'renderMetabox'],
            $screen,
            'side',
            'high'
        );
    }

    /**
     * Get the correct screen for WooCommerce orders (supports HPOS).
     */
    private function getOrderScreen(): string
    {
        // Support for WooCommerce HPOS (High-Performance Order Storage)
        if (class_exists('\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController')) {
            $controller = wc_get_container()->get(\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class);
            if ($controller->custom_orders_table_usage_is_enabled()) {
                return wc_get_page_screen_id('shop-order');
            }
        }
        return 'shop_order';
    }

    /**
     * Render the metabox content.
     *
     * @param mixed $post_or_order Post object or WC_Order.
     */
    public function renderMetabox($post_or_order): void
    {
        // Get order object (supports both HPOS and legacy)
        if ($post_or_order instanceof \WC_Order) {
            $order = $post_or_order;
        } else {
            $order = wc_get_order($post_or_order->ID);
        }

        if (!$order) {
            echo '<p>' . esc_html__('Order not found.', 'gophr-same-day') . '</p>';
            return;
        }

        $job_id = $order->get_meta(GOPHR_METHOD_ID . '_delivery_job_id', true);

        if (empty($job_id)) {
            echo '<p>' . esc_html__('No Gophr shipment created for this order yet.', 'gophr-same-day') . '</p>';
            return;
        }

        // Get job status - for now we'll show "draft" state (Step 2)
        $job_status = $order->get_meta(GOPHR_METHOD_ID . '_job_status', true) ?: 'draft';

        $this->renderJobInfo($order, $job_id, $job_status);
    }

    /**
     * Render job information and action buttons.
     */
    private function renderJobInfo(\WC_Order $order, string $job_id, string $job_status): void
    {
        $order_id = $order->get_id();
        $nonce = wp_create_nonce('gophr_metabox_action');

        // Build Gophr job URL
        $is_sandbox = get_option('gophr_environment', 'production') === 'sandbox';
        $base_url = $is_sandbox ? 'https://sandbox.book.gophr.com' : 'https://book.gophr.com';
        $job_url = $base_url . '/jobs/' . $job_id;

        ?>
        <div class="gophr-shipment-metabox">
            <p>
                <strong><?php esc_html_e('Job ID:', 'gophr-same-day'); ?></strong>
                <a href="<?php echo esc_url($job_url); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html($job_id); ?>
                </a>
            </p>

            <?php if ($job_status === 'draft') : ?>
                <p>
                    <button type="button" 
                            class="button button-primary gophr-action-btn" 
                            data-action="edit_draft_job" 
                            data-order-id="<?php echo esc_attr($order_id); ?>"
                            data-nonce="<?php echo esc_attr($nonce); ?>">
                        <?php esc_html_e('Edit Draft Job', 'gophr-same-day'); ?>
                    </button>
                </p>

                <p><strong><?php esc_html_e('Step 2: Accept your shipment.', 'gophr-same-day'); ?></strong></p>

                <p>
                    <button type="button" 
                            class="button button-primary gophr-action-btn" 
                            style="background-color: #00a32a; border-color: #00a32a;"
                            data-action="accept_shipment" 
                            data-order-id="<?php echo esc_attr($order_id); ?>"
                            data-nonce="<?php echo esc_attr($nonce); ?>">
                        <?php esc_html_e('Accept Shipment', 'gophr-same-day'); ?>
                    </button>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Enqueue admin scripts for the metabox.
     */
    public function enqueueScripts(string $hook): void
    {
        // Only load on order edit pages
        if (!in_array($hook, ['post.php', 'woocommerce_page_wc-orders'], true)) {
            return;
        }

        $screen = get_current_screen();
        if (!$screen || !in_array($screen->id, ['shop_order', 'woocommerce_page_wc-orders'], true)) {
            return;
        }

        wp_enqueue_script(
            'gophr-order-metabox',
            GOPHR_URL . 'assets/js/order-metabox.js',
            ['jquery'],
            GOPHR_VERSION,
            true
        );

        wp_localize_script('gophr-order-metabox', 'gophrMetabox', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'strings' => [
                'processing' => __('Processing...', 'gophr-same-day'),
                'error' => __('An error occurred. Please try again.', 'gophr-same-day'),
            ],
        ]);
    }

    /**
     * Handle Edit Draft Job AJAX action (dummy for now).
     */
    public function handleEditDraftJob(): void
    {
        check_ajax_referer('gophr_metabox_action', 'nonce');

        if (!current_user_can('edit_shop_orders')) {
            wp_send_json_error(['message' => __('Permission denied.', 'gophr-same-day')]);
        }

        $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;

        if (!$order_id) {
            wp_send_json_error(['message' => __('Invalid order ID.', 'gophr-same-day')]);
        }

        // TODO: Implement actual edit draft job functionality
        wp_send_json_success([
            'message' => __('Edit Draft Job clicked (functionality coming soon).', 'gophr-same-day'),
        ]);
    }

    /**
     * Handle Accept Shipment AJAX action (dummy for now).
     */
    public function handleAcceptShipment(): void
    {
        check_ajax_referer('gophr_metabox_action', 'nonce');

        if (!current_user_can('edit_shop_orders')) {
            wp_send_json_error(['message' => __('Permission denied.', 'gophr-same-day')]);
        }

        $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;

        if (!$order_id) {
            wp_send_json_error(['message' => __('Invalid order ID.', 'gophr-same-day')]);
        }

        // TODO: Implement actual accept shipment functionality
        wp_send_json_success([
            'message' => __('Accept Shipment clicked (functionality coming soon).', 'gophr-same-day'),
        ]);
    }
}
