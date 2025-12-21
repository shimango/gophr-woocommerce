<?php
/**
 * Plugin Name: Gophr Same-Day Delivery
 * Plugin URI: https://app.gophr.com/login
 * Description: Provides Gophr as a carrier
 * Version:           1.0.0
 * Author:            Jairo Kasmierchcki
 * Author URI:        https://github.com/shimango
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gophr-same-day
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      8.1
 */

use GophrSameDay\Admin\SettingsPage;
use GophrSameDay\Integration\GophrShippingMethod;


// If this file is called directly, abort.
defined('ABSPATH') || exit;

// Define plugin constants
define('GOPHR_SAME_DAY_VERSION', '1.0.0');
define('GOPHR_SAME_DAY_PATH', plugin_dir_path(__FILE__));
define('GOPHR_SAME_DAY_URL', plugin_dir_url(__FILE__));
define('GOPHR_SAME_DAY_BASENAME', plugin_basename(__FILE__));

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return; // Exit if WooCommerce isn't active.
}

/**
 * Autoload Composer dependencies
 */
if (file_exists(GOPHR_SAME_DAY_PATH . 'vendor/autoload.php')) {
    require_once GOPHR_SAME_DAY_PATH . 'vendor/autoload.php';
}

/**
 * Prevent direct instantiation of the main class outside this file.
 */
if (!class_exists('GophrSameDay\Plugin')) {
    /**
     * Main plugin class
     */
    final class GophrSameDayPlugin
    {
        /**
         * Plugin instance.
         *
         * @var GophrSameDayPlugin|null
         */
        private static ?GophrSameDayPlugin $instance = null;

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
            register_activation_hook(GOPHR_SAME_DAY_BASENAME, [self::class, 'activate']);
            register_deactivation_hook(GOPHR_SAME_DAY_BASENAME, [self::class, 'deactivate']);

            // Load text domain
            add_action('init', [$this, 'loadTextDomain']);

            // Enqueue assets
            add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
            add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);

            // In setupHooks() method:
            add_action('admin_menu', [$this, 'addMenuPage']);

            // WooCommerce
            add_action('woocommerce_shipping_init', [$this, 'gophrShippingInit']);
            add_filter('woocommerce_shipping_methods', [$this, 'addGophrShippingMethod']);

            // Register our gophrSettingsInit to the admin_init action hook.
            add_action( 'admin_init', [$this, 'gophrSettingsInit']);

            // Register our gophrOptionsPage to the admin_menu action hook.
            add_action( 'admin_menu', [$this, 'gophrOptionsPage']);
        }


        public function gophrOptionsPage(): void
        {
            SettingsPage::getInstance()->gophr_same_day_options_page();
        }

        public function gophrSettingsInit(): void
        {
            SettingsPage::getInstance()->wporg_settings_init();
        }

        public function addMenuPage(): void
        {
            if (!class_exists('GophrSameDay\Admin\MenuPage')) {
                require_once GOPHR_SAME_DAY_PATH . 'src/Admin/MenuPage.php';
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
            $methods['your_delivery'] = GophrShippingMethod::class;
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
                GOPHR_SAME_DAY_URL . 'assets/css/frontend.css',
                [],
                GOPHR_SAME_DAY_VERSION
            );

            wp_enqueue_script(
                'gophr-same-day-frontend',
                GOPHR_SAME_DAY_URL . 'assets/js/frontend.js',
                ['jquery'],
                GOPHR_SAME_DAY_VERSION,
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
                GOPHR_SAME_DAY_URL . 'assets/css/admin.css',
                [],
                GOPHR_SAME_DAY_VERSION
            );
        }
    }
}

// Initialize the plugin
GophrSameDayPlugin::getInstance();