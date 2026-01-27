<?php

namespace Gophr\Woocommerce\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SettingsPage {

    private static ?SettingsPage $instance = null;

    public static function getInstance(): SettingsPage {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('plugin_action_links_' . GOPHR_BASENAME, [$this, 'addPluginActionLinks']);
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addPluginActionLinks(array $links): array {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url('admin.php?page=gophr-settings')),
            esc_html__('Settings', 'gophr-same-day')
        );

        array_unshift($links, $settings_link);
        return $links;
    }

    public function addAdminMenu(): void {
        add_menu_page(
            esc_html__('Gophr Settings', 'gophr-same-day'),
            esc_html__('Gophr', 'gophr-same-day'),
            'manage_options',
            'gophr-settings',
            [$this, 'settingsPageCallback'],
            'dashicons-cart',
            58
        );
    }

    public function settingsPageCallback(): void {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(
                    esc_html__('You do not have sufficient permissions to access this page.', 'gophr-same-day')
            );
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php settings_errors('gophr_settings_group'); ?>

            <form method="post" action="options.php">
                <?php
                settings_fields('gophr_settings_group');
                do_settings_sections('gophr-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function registerSettings(): void {
        $current_section = 'default';

        foreach (FormFields::getFields() as $key => $field) {
            if ($field['type'] === 'title') {
                $callback = null;
                if (!empty($field['description'])) {
                    $callback = function() use ($field) {
                        echo wp_kses_post($field['description']);
                    };
                }

                add_settings_section(
                    $key,
                    esc_html($field['title']),
                    $callback,
                    'gophr-settings'
                );

                $current_section = $key;
            } else {
                // Register with sanitization callback
                register_setting(
                    'gophr_settings_group',
                    $key,
                    [
                        'sanitize_callback' => [$this, 'sanitizeField'],
                        'default' => $field['default'] ?? '',
                    ]
                );

                add_settings_field(
                    $key,
                    !empty($field['title']) ? esc_html($field['title']) : '',
                    [$this, 'fieldCallback'],
                    'gophr-settings',
                    $current_section,
                    $field + ['key' => $key]
                );
            }
        }
    }

    /**
     * Sanitize field values based on type.
     *
     * @param mixed $value Field value.
     * @return mixed Sanitized value.
     */
    public function sanitizeField($value) {
        if (is_array($value)) {
            return array_map([$this, 'sanitizeField'], $value);
        }

        return sanitize_text_field($value);
    }

    public function fieldCallback(array $args): void {
        $key = $args['key'];
        $default = $args['default'] ?? '';
        $value = get_option($key, $default);
        $type = $args['type'];

        // Sanitize output
        $value = is_array($value) ? $value : sanitize_text_field($value);

        if ($type === 'single_select_country') {
            $type = 'country';
        }

        if (in_array($type, ['text', 'checkbox', 'select', 'country'])) {
            $wc_args = [
                'type' => $type,
                'label' => $args['label'] ?? '',
                'description' => $args['description'] ?? '',
                'options' => $args['options'] ?? [],
                'custom_attributes' => isset($args['css']) ? ['style' => esc_attr($args['css'])] : [],
            ];

            woocommerce_form_field($key, $wc_args, $value);

        } elseif ($type === 'working_hours') {
            $this->render_working_hours_field($key, $value, $args);
        } elseif ($type === 'services') {
            $this->render_services_field($key, $value, $args);
        } elseif ($type === 'textarea') {
            $this->render_textarea_field($key, $value, $args);
        } elseif ($type === 'parcel_flags') {
            $this->render_parcel_flags_field($key, $value, $args);
        }
    }

    private function render_working_hours_field(string $key, $value, array $args): void {
        $days = [
            'monday' => __('Monday', 'gophr-same-day'),
            'tuesday' => __('Tuesday', 'gophr-same-day'),
            'wednesday' => __('Wednesday', 'gophr-same-day'),
            'thursday' => __('Thursday', 'gophr-same-day'),
            'friday' => __('Friday', 'gophr-same-day'),
            'saturday' => __('Saturday', 'gophr-same-day'),
            'sunday' => __('Sunday', 'gophr-same-day'),
        ];

        $value = is_array($value) ? $value : [];

        echo '<table class="form-table">';
        foreach ($days as $d_key => $d_label) {
            $from = isset($value[$d_key]['from']) ? esc_attr($value[$d_key]['from']) : '';
            $to = isset($value[$d_key]['to']) ? esc_attr($value[$d_key]['to']) : '';

            printf(
                '<tr><th>%s</th><td><input type="time" name="%s" value="%s" /> %s <input type="time" name="%s" value="%s" /></td></tr>',
                esc_html($d_label),
                esc_attr($key . '[' . $d_key . '][from]'),
                $from,
                esc_html__('to', 'gophr-same-day'),
                esc_attr($key . '[' . $d_key . '][to]'),
                $to
            );
        }
        echo '</table>';

        if (isset($args['description'])) {
            printf('<p class="description">%s</p>', wp_kses_post($args['description']));
        }
    }

    private function render_services_field(string $key, $value, array $args): void {
        $services = [
            '0' => __('Standard Delivery', 'gophr-same-day'),
            'high_priority' => __('High Priority Delivery', 'gophr-same-day'),
            'rush' => __('Rush Delivery', 'gophr-same-day'),
        ];

        $value = is_array($value) ? $value : [];

        echo '<fieldset>';
        foreach ($services as $s_key => $s_label) {
            $checked = isset($value[$s_key]) && $value[$s_key] === 'yes' ? 'checked="checked"' : '';

            printf(
                '<label><input type="checkbox" name="%s" value="yes" %s /> %s</label><br />',
                esc_attr($key . '[' . $s_key . ']'),
                $checked,
                esc_html($s_label)
            );
        }
        echo '</fieldset>';

        if (isset($args['description'])) {
            printf('<p class="description">%s</p>', wp_kses_post($args['description']));
        }
    }

    private function render_textarea_field(string $key, $value, array $args): void {
        $value = is_string($value) ? $value : '';
        $css = isset($args['css']) ? esc_attr($args['css']) : 'width:100%;max-width:400px;';

        printf(
            '<textarea name="%s" rows="3" style="%s">%s</textarea>',
            esc_attr($key),
            $css,
            esc_textarea($value)
        );

        if (isset($args['description'])) {
            printf('<p class="description">%s</p>', wp_kses_post($args['description']));
        }
    }

    private function render_parcel_flags_field(string $key, $value, array $args): void {
        $flags = [
            'is_food' => __('Food', 'gophr-same-day'),
            'is_fragile' => __('Fragile', 'gophr-same-day'),
            'is_liquid' => __('Liquid', 'gophr-same-day'),
            'is_glass' => __('Glass', 'gophr-same-day'),
        ];

        $value = is_array($value) ? $value : [];

        echo '<fieldset>';
        foreach ($flags as $f_key => $f_label) {
            $checked = isset($value[$f_key]) && $value[$f_key] === 'yes' ? 'checked="checked"' : '';

            printf(
                '<label><input type="checkbox" name="%s" value="yes" %s /> %s</label><br />',
                esc_attr($key . '[' . $f_key . ']'),
                $checked,
                esc_html($f_label)
            );
        }
        echo '</fieldset>';

        if (isset($args['description'])) {
            printf('<p class="description">%s</p>', wp_kses_post($args['description']));
        }
    }
}
