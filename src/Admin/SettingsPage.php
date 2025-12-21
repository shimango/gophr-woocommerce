<?php

namespace GophrSameDay\Admin;

class SettingsPage
{
    public static function getInstance(): SettingsPage
    {
            return new self();
    }

    /**
     * @internal never define functions inside callbacks.
     * these functions could be run multiple times; this would result in a fatal error.
     */

    /**
     * custom option and settings
     */
    public function wporg_settings_init() {
        // Register a new setting for "gophr-same-day" page.
        register_setting( 'gophr-same-day', 'gophr-same-day_options' );

        // Register a new section in the "gophr-same-day" page.
        add_settings_section(
            'gophr-same-day_section_developers',
            __( 'The Matrix has you.', 'gophr-same-day' ), 'gophr_same_day_section_developers_callback',
            'gophr-same-day'
        );

        // Register a new field in the "gophr-same-day_section_developers" section, inside the "gophr-same-day" page.
        add_settings_field(
            'gophr-same-day_field_pill', // As of WP 4.6 this value is used only internally.
            // Use $args' label_for to populate the id inside the callback.
            __( 'Pill', 'gophr-same-day' ),
            'gophr-same-day_field_pill_cb',
            'gophr-same-day',
            'gophr-same-day_section_developers',
            array(
                'label_for'         => 'gophr-same-day_field_pill',
                'class'             => 'gophr-same-day_row',
                'gophr-same-day_custom_data' => 'custom',
            )
        );
    }

    /**
     * Custom option and settings:
     *  - callback functions
     */


    /**
     * Developers section callback function.
     *
     * @param array $args  The settings array, defining title, id, callback.
     */
    function gophr_same_day_section_developers_callback( $args ) {
        ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'gophr-same-day' ); ?></p>
        <?php
    }

    /**
     * Pill field callbakc function.
     *
     * WordPress has magic interaction with the following keys: label_for, class.
     * - the "label_for" key value is used for the "for" attribute of the <label>.
     * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
     * Note: you can add custom key value pairs to be used inside your callbacks.
     *
     * @param array $args
     */
    function gophr_same_day_field_pill_cb( $args ) {
        // Get the value of the setting we've registered with register_setting()
        $options = get_option( 'gophr-same-day_options' );
        ?>
        <select
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['gophr-same-day_custom_data'] ); ?>"
            name="gophr-same-day_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
            <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'red pill', 'gophr-same-day' ); ?>
            </option>
            <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
                <?php esc_html_e( 'blue pill', 'gophr-same-day' ); ?>
            </option>
        </select>
        <p class="description">
            <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'gophr-same-day' ); ?>
        </p>
        <p class="description">
            <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'gophr-same-day' ); ?>
        </p>
        <?php
    }

    /**
     * Add the top level menu page.
     */
    function gophr_same_day_options_page() {
        add_menu_page(
            'Gophr Same Day',
            'Gophr Same Day Options',
            'manage_options',
            'gophr-same-day',
            'gophr-same-day_options_page_html'
        );
    }

    /**
     * Top level menu callback function
     */
    function wporg_options_page_html() {
        // check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // add error/update messages

        // check if the user have submitted the settings
        // WordPress will add the "settings-updated" $_GET parameter to the url
        if ( isset( $_GET['settings-updated'] ) ) {
            // add settings saved message with the class of "updated"
            add_settings_error( 'gophr-same-day_messages', 'gophr-same-day_message', __( 'Settings Saved', 'gophr-same-day' ), 'updated' );
        }

        // show error/update messages
        settings_errors( 'gophr-same-day_messages' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                // output security fields for the registered setting "gophr-same-day"
                settings_fields( 'gophr-same-day' );
                // output setting sections and their fields
                // (sections are registered for "gophr-same-day", each field is registered to a specific section)
                do_settings_sections( 'gophr-same-day' );
                // output save settings button
                submit_button( 'Save Settings' );
                ?>
            </form>
        </div>
        <?php
    }
}