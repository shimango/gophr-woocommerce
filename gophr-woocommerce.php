<?php

/**
 * Plugin Name: Gophr Same-Day Delivery
 * Plugin URI: https://app.gophr.com/login
 * Description: Gophr Same-Day Delivery for WooCommerce
 * Version: 1.0.0
 * Author: Jairo Kasmierchcki
 * Author URI: https://github.com/shimango
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gophr-same-day
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * WC requires at least: 7.0
 * WC tested up to: 8.5
 */

defined('ABSPATH') || exit;

// Use WordPress-compliant constants instead of static class
define('GOPHR_VERSION', '1.0.0');
define('GOPHR_METHOD_ID', 'gophr-same-day');
define('GOPHR_PLUGIN_NAME', 'gophr-woocommerce');
define('GOPHR_FILE', __FILE__);
define('GOPHR_PATH', plugin_dir_path(__FILE__));
define('GOPHR_URL', plugin_dir_url(__FILE__));
define('GOPHR_BASENAME', plugin_basename(__FILE__));

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>' .
            esc_html__('Gophr Same-Day Delivery requires WooCommerce to be installed and active.', 'gophr-same-day') .
            '</p></div>';
    });
    return;
}

// Autoload Composer dependencies
require_once GOPHR_PATH . 'vendor/autoload.php';

// Initialize the plugin
Gophr\Woocommerce\Plugin\GophrPlugin::getInstance();
