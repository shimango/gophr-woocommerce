<?php

namespace Gophr\Woocommerce\Admin;

class SettingsPage
{
    public static function getInstance(): SettingsPage
    {
            return new self();
    }

    /**
     * Constructor: Set up hooks.
     */
    public function __construct() {
        add_filter( 'plugin_action_links_' . plugin_basename( \Gophr_Constants::$GOPHR_SAME_DAY_FILE ), [ $this, 'addPluginActionLinks'] );
        add_action( 'admin_menu', [ $this, 'addAdminMenu'] );
        add_action( 'admin_init', [ $this, 'registerSettings'] );
    }

    /**
     * Add the settings link to the plugin action links.
     *
     * @param array $links Existing action links.
     * @return array Modified action links.
     */
    public function addPluginActionLinks( array $links ): array
    {
        $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=gophr-settings' ) ) . '">' . esc_html__( 'Settings', 'gophr-same-day' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Add the admin menu page for settings.
     */
    public function addAdminMenu() {
        add_menu_page(
            esc_html__( 'Gophr Settings', 'gophr-same-day' ),  // Page title.
            esc_html__( 'Gophr', 'gophr-same-day' ),          // Menu title.
            'manage_options',                                 // Capability (admin access).
            'gophr-settings',                                 // Menu slug.
            [ $this, 'settingsPageCallback'],              // Callback function to render the page.
            'dashicons-cart',                                 // Icon (optional, use a dashicon or URL).
            58                                                // Position (after WooCommerce, which is 55).
        );
    }

    /**
     * Callback function to render the settings page.
     */
    public function settingsPageCallback(): void
    {
        echo '
        <div class="wrap">
            <h1>' . esc_html__('Gophr Same-Day Delivery Settings', 'gophr-same-day') . '</h1>
            <form method="post" action="options.php">
    ';
        settings_fields('gophr_settings_group');
        do_settings_sections('gophr-settings');
        wp_nonce_field('gophr_save_settings', 'gophr_nonce');
        submit_button();
        echo '
            </form>
        </div>
    ';
    }

    /**
     * Register settings, sections, and fields based on the $fields array.
     */
    public function registerSettings(): void
    {
        $current_section = 'default';

        foreach ( FormFields::getFields() as $key => $field ) {
            if ( $field['type'] === 'title' ) {
                $callback = null;
                if ( ! empty( $field['description'] ) ) {
                    $callback = function() use ( $field ) {
                        echo wp_kses_post( $field['description'] );
                    };
                }

                add_settings_section( $key, $field['title'], $callback, 'gophr-settings' );
                $current_section = $key;
            } else {
                register_setting( 'gophr_settings_group', $key );
                add_settings_field(
                    $key,
                    $field['title'] ?? '',
                    [ $this, 'fieldCallback'],
                    'gophr-settings',
                    $current_section,
                    $field + [ 'key' => $key ]
                );
            }
        }
    }

    /**
     * Generic callback to render fields using WooCommerce form fields where possible, or custom HTML for special types.
     *
     * @param array $args Field arguments.
     */
    public function fieldCallback(array $args): void
    {
        $key = $args['key'];
        $default = $args['default'] ?? '';
        $value = sanitize_text_field(get_option( $key, $default ));
        $type = $args['type'];

        if ( $type === 'single_select_country' ) {
            $type = 'country';
        }

        if ( in_array( $type, [ 'text', 'checkbox', 'select', 'country' ] ) ) {
            $wc_args = [
                'type' => $type,
                'label' => $args['label'] ?? '',
                'description' => $args['description'] ?? '',
                'options' => $args['options'] ?? [],
                'custom_attributes' => isset( $args['css'] ) ? [ 'style' => $args['css'] ] : [],
            ];
            woocommerce_form_field( $key, $wc_args, $value );
        } elseif ( $type === 'working_hours' ) {
            // Custom rendering for working hours: table with days and time inputs.
            $days = [
                'monday' => __( 'Monday', 'gophr-same-day' ),
                'tuesday' => __( 'Tuesday', 'gophr-same-day' ),
                'wednesday' => __( 'Wednesday', 'gophr-same-day' ),
                'thursday' => __( 'Thursday', 'gophr-same-day' ),
                'friday' => __( 'Friday', 'gophr-same-day' ),
                'saturday' => __( 'Saturday', 'gophr-same-day' ),
                'sunday' => __( 'Sunday', 'gophr-same-day' ),
            ];

            $value = is_array( $value ) ? $value : [];
            echo '<table class="form-table">';

            foreach ( $days as $d_key => $d_label ) {
                $from = $value[$d_key]['from'] ?? '';
                $to = $value[$d_key]['to'] ?? '';
                echo '<tr><th>' . esc_html( $d_label ) . '</th><td>';
                echo '<input type="time" name="' . esc_attr( $key ) . '[' . esc_attr( $d_key ) . '][from]" value="' . esc_attr( $from ) . '" /> to ';
                echo '<input type="time" name="' . esc_attr( $key ) . '[' . esc_attr( $d_key ) . '][to]" value="' . esc_attr( $to ) . '" />';
                echo '</td></tr>';
            }

            echo '</table>';

            if ( isset( $args['description'] ) ) {
                echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
            }
        } elseif ( $type === 'services' ) {
            // Custom rendering for services: checkboxes for different services (assumed based on typical setup).
            $services = [
                '0' => __( 'Standard Delivery', 'gophr-same-day' ),
                'high_priority' => __( 'High Priority Delivery', 'gophr-same-day' ),
                'rush' => __( 'Rush Delivery', 'gophr-same-day' ),
                // Add more services if known from API/docs.
            ];

            $value = is_array( $value ) ? $value : [];
            echo '<fieldset>';

            foreach ( $services as $s_key => $s_label ) {
                $checked = isset( $value[ $s_key ] ) && $value[ $s_key ] === 'yes' ? 'checked="checked"' : '';
                echo '<label><input type="checkbox" name="' . esc_attr( $key ) . '[' . esc_attr( $s_key ) . ']" value="yes" ' . $checked . ' /> ' . esc_html( $s_label ) . '</label><br />';
            }

            echo '</fieldset>';
            if ( isset( $args['description'] ) ) {
                echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
            }
        } else {
            echo '<p>' . esc_html__( 'Unsupported field type: ', 'gophr-same-day' ) . esc_html( $type ) . '</p>';
        }
    }
}