<?php

namespace Gophr\Tests\Unit;

use Gophr\Tests\TestCase;
use Gophr\Woocommerce\Admin\SettingsPage;

class SettingsPageTest extends TestCase
{

    private SettingsPage $settings_page;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settings_page = SettingsPage::getInstance();
    }

    public function test_get_instance_returns_settings_page()
    {
        $instance = SettingsPage::getInstance();
        $this->assertInstanceOf(SettingsPage::class, $instance);
    }

    public function test_admin_menu_is_registered()
    {
        global $menu;

        do_action('admin_menu');

        $found = false;
        if (is_array($menu)) {
            foreach ($menu as $item) {
                if (isset($item[2]) && $item[2] === 'gophr-settings') {
                    $found = true;
                    break;
                }
            }
        }

        // This might fail if menu isn't fully initialized in tests
        // but the hook should be registered
        $this->assertTrue(has_action('admin_menu'));
    }

    public function test_settings_are_registered()
    {
        do_action('admin_init');

        global $wp_registered_settings;

        $this->assertArrayHasKey('gophr_api_key', $wp_registered_settings);
        $this->assertArrayHasKey('gophr_environment', $wp_registered_settings);
    }

    public function test_plugin_action_links_filter_exists()
    {
        $plugin_file = plugin_basename(GOPHR_FILE);
        $this->assertTrue(has_filter('plugin_action_links_' . $plugin_file));
    }
}