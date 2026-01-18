<?php

/**
 * PHPUnit Bootstrap File
 * Save as: tests/bootstrap.php
 */

// Composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Mock Constants class for tests
class Constants
{
    public static string $GOPHR_SAME_DAY_VERSION = '1.0.0';
    public static string $GOPHR_SAME_DAY_METHOD_ID = 'gophr-same-day';
    public static string $GOPHR_SAME_DAY_PLUGIN = 'gophr-woocommerce';
    public static string $GOPHR_SAME_DAY_FILE;
    public static string $GOPHR_SAME_DAY_PATH;
    public static string $GOPHR_SAME_DAY_URL;
    public static string $GOPHR_SAME_DAY_BASENAME;

    public static function init(): void
    {
        self::$GOPHR_SAME_DAY_FILE = dirname(__DIR__) . '/gophr-woocommerce.php';
        self::$GOPHR_SAME_DAY_PATH = dirname(__DIR__) . '/';
        self::$GOPHR_SAME_DAY_URL = 'http://localhost/wp-content/plugins/gophr-woocommerce/';
        self::$GOPHR_SAME_DAY_BASENAME = 'gophr-woocommerce/gophr-woocommerce.php';
    }
}

Constants::init();

// Load WordPress test framework
$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    $_tests_dir = rtrim(sys_get_temp_dir(), '/\\') . '/wordpress-tests-lib';
}

if (!file_exists($_tests_dir . '/includes/functions.php')) {
    echo "Could not find $_tests_dir/includes/functions.php\n";
    exit(1);
}

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin()
{
    // Activate WooCommerce first
    require dirname(__DIR__) . '/vendor/woocommerce/woocommerce/woocommerce.php';

    // Then activate our plugin
    require dirname(__DIR__) . '/gophr-woocommerce.php';
}

tests_add_filter('muplugins_loaded', '_manually_load_plugin');
require $_tests_dir . '/includes/bootstrap.php';

/**
 * Base Test Case
 * Save as: tests/TestCase.php
 */



/**
 * Test GophrShippingMethod Class
 * Save as: tests/Unit/GophrShippingMethodTest.php
 */



/**
 * Test Payload Utility Class
 * Save as: tests/Unit/PayloadTest.php
 */



/**
 * Test GophrPlugin Class
 * Save as: tests/Unit/GophrPluginTest.php
 */



/**
 * Test FormFields Class
 * Save as: tests/Unit/FormFieldsTest.php
 */



/**
 * Test SettingsPage Class
 * Save as: tests/Unit/SettingsPageTest.php
 */



/**
 * Integration Test for Order Flow
 * Save as: tests/Integration/OrderFlowTest.php
 */



/**
 * Integration Test for Settings
 * Save as: tests/Integration/SettingsIntegrationTest.php
 */



/**
 * Mock Tests for API Calls
 * Save as: tests/Unit/ApiMockTest.php
 */

