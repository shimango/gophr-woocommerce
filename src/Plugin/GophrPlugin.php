<?php

namespace Gophr\Woocommerce\Plugin;

use Gophr\Woocommerce\Admin\SettingsPage;
use Gophr\Woocommerce\Integration\GophrShippingMethod;

class GophrPlugin
{
    /**
     * Plugin instance.
     *
     * @var GophrPlugin|null
     */
    private static ?GophrPlugin $instance = null;


    /**
     * Get the singleton instance.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        $this->setupHooks();
    }

    /**
     * Register all hooks.
     */
    private function setupHooks(): void
    {
        // Activation / deactivation hooks
        register_activation_hook(GOPHR_FILE, [$this, 'activate']);
        register_deactivation_hook(GOPHR_FILE, [$this, 'deactivate']);

        // Load text domain
        add_action('init', [$this, 'loadTextDomain']);

        // Enqueue assets
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);

        // WooCommerce
        add_filter('woocommerce_shipping_methods', [$this, 'addGophrShippingMethod']);

        // Initialize the settings page class.
        if (class_exists(SettingsPage::class)) {
            SettingsPage::getInstance();
        }
    }

    /**
     * Add the shipping method to WooCommerce.
     */
    public function addGophrShippingMethod(array $methods): array
    {
        $methods[GOPHR_METHOD_ID] = GophrShippingMethod::class;
        return $methods;
    }

    /**
     * Called on plugin activation.
     */
    public static function activate(): void
    {
        // flush_rewrite_rules(); // if using custom post types or taxonomies
    }

    /**
     * Called on plugin deactivation.
     */
    public static function deactivate(): void
    {
        // flush_rewrite_rules();
        // delete_transient('gophr_shipping_rates');
    }

    /**
     * Load plugin text domain for translations.
     */
    public function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'gophr-same-day',
            false,
            dirname(GOPHR_BASENAME) . '/languages'
        );
    }

    /**
     * Enqueue frontend scripts and styles.
     */
    public function enqueueFrontendAssets(): void
    {
        wp_enqueue_style(
            'gophr-same-day-frontend',
            GOPHR_URL . 'assets/css/frontend.css',
            [],
            GOPHR_VERSION
        );

        wp_enqueue_script(
            'gophr-same-day-frontend',
            GOPHR_URL . 'assets/js/frontend.js',
            ['jquery'],
            GOPHR_VERSION,
            true
        );
    }

    /**
     * Enqueue admin scripts and styles.
     */
    public function enqueueAdminAssets(): void
    {
        wp_enqueue_style(
            'gophr-same-day-admin',
            GOPHR_URL . 'assets/css/admin.css',
            [],
            GOPHR_VERSION
        );
    }
}