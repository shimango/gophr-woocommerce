<?php
/**
 * Plugin Name: Gophr
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


// Initialize the plugin
\GophrSameDay\Plugin\GophrSameDayPlugin::getInstance();