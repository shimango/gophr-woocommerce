<?php

/**
 * Plugin Name: Gophr Same-Day Delivery
 * Plugin URI: https://app.gophr.com/login
 * Description: Gophr Same-Day Delivery for WooCommerce
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

// If this file is called directly, abort.
defined('ABSPATH') || exit;

// Define plugin constants

// Define plugin constants for easier reference.
define('GOPHR_SAME_DAY_VERSION', '1.0.0');
define( 'GOPHR_SAME_DAY_FILE', __FILE__ );
define('GOPHR_SAME_DAY_PATH', plugin_dir_path(__FILE__));
define('GOPHR_SAME_DAY_URL', plugin_dir_url(__FILE__));
define('GOPHR_SAME_DAY_BASENAME', plugin_basename(__FILE__));

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return; // Exit if WooCommerce isn't active.
}

/**
 * Autoload Composer dependencies
 */
require_once GOPHR_SAME_DAY_PATH . 'vendor/autoload.php';

// Initialize the plugin
Gophr\Woocommerce\Plugin\GophrPlugin::getInstance();