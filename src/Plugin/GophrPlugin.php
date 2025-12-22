<?php

namespace Gophr\Woocommerce\Plugin;

use Gophr\Woocommerce\Admin\MenuPage;
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
        register_activation_hook(\Constants::$GOPHR_SAME_DAY_BASENAME, [self::class, 'activate']);
        register_deactivation_hook(\Constants::$GOPHR_SAME_DAY_BASENAME, [self::class, 'deactivate']);

        // Load text domain
        add_action('init', [$this, 'loadTextDomain']);

        // Enqueue assets
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);

        // In setupHooks() method:
//        add_action('admin_menu', [MenuPage::getInstance(), 'addMenuPage']);

        // WooCommerce
        add_action('woocommerce_shipping_init', [$this, 'gophrShippingInit']);
        add_filter('woocommerce_shipping_methods', [$this, 'addGophrShippingMethod']);

        // Initialize the settings page class.
        if ( class_exists(SettingsPage::class)) {
            SettingsPage::getInstance();
        }
    }

    /**
     * Initialize the shipping method.
     */
    public function gophrShippingInit(): void
    {
        GophrShippingMethod::getInstance();
    }

    /**
     * Add the shipping method to WooCommerce.
     */
    public function addGophrShippingMethod(array $methods): array
    {
        $methods[\Constants::$GOPHR_SAME_DAY_METHOD_ID] = GophrShippingMethod::class;
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
    }

    /**
     * Load plugin text domain for translations.
     */
    public function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'gophr-same-day',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    /**
     * Enqueue frontend scripts and styles.
     */
    public function enqueueFrontendAssets(): void
    {
        wp_enqueue_style(
            'gophr-same-day-frontend',
            \Constants::$GOPHR_SAME_DAY_URL . 'assets/css/frontend.css',
            [],
            \Constants::$GOPHR_SAME_DAY_VERSION
        );

        wp_enqueue_script(
            'gophr-same-day-frontend',
            \Constants::$GOPHR_SAME_DAY_URL . 'assets/js/frontend.js',
            ['jquery'],
            \Constants::$GOPHR_SAME_DAY_VERSION,
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
            \Constants::$GOPHR_SAME_DAY_URL . 'assets/css/admin.css',
            [],
            \Constants::$GOPHR_SAME_DAY_VERSION
        );
    }
}