<?php

namespace Gophr\Woocommerce\Admin;

class FormFields
{
    public  static function getFields(): array
    {
        return [
            'gophr_carrier_service_settings' => [
                'title' => __('Carrier service', 'gophr-woocommerce'),
                'type' => 'title',
            ],
            'gophr_enable' => [
                'title' => __('Enable/Disable', 'gophr-woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable this shipping method', 'gophr-woocommerce'),
                'default' => 'yes'
            ],
            'gophr_shipping_title' => [
                'carrier_title' => __('Shipping Title', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'gophr-woocommerce'),
                'default' => __('Gophr Same-Day', 'gophr-woocommerce')
            ],
            'gophr_api_settings' => [
                'title' => __('API Settings', 'gophr-woocommerce'),
                'type' => 'title',
            ],
            'gophr_api_key' => [
                'title' => __('Gophr API Key', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Create account at book.gophr.com and request API key though live chat or email help@gophr.com.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_environment' => [
                'title' => __('API Key Mode', 'gophr-woocommerce'),
                'type' => 'select',
                'default' => 'production',
                'options' => [
                    'production' => __('Production', 'gophr-woocommerce'),
                    'sandbox' => __('Sandbox', 'gophr-woocommerce'),
                ],
                'description' => __('Set as Test to switch to Gophr api test servers. Transaction will be treated as sample transactions by Gophr.', 'gophr-woocommerce')
            ],
            'gophr_availability' => [
                'title' => __('Availability', 'gophr-woocommerce'),
                'type' => 'title',
                'description' => '',
            ],
            'gophr_working_hours' => [
                'type' => 'working_hours',
            ],
            'gophr_preparation_time' => [
                'title' => __('Order preparation time', 'gophr-woocommerce'),
                'type' => 'text',
                'css' => "width:2em",
                'description' => __('Order preparation and packaging time in hours.', 'gophr-woocommerce'),
                'default' => '1',
            ],
            'gophr_origin_info' => [
                'title' => __('Origin Information', 'gophr-woocommerce'),
                'type' => 'title',
            ],
            'gophr_user_name' => [
                'title' => __('Your Name', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Enter your name', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_origin_company_name' => [
                'title' => __('Company Name', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Your business/attention name.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_origin_address1' => [
                'title' => __('Origin Address', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Address for the <strong>sender</strong>.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_origin_address2' => [
                'title' => __('Origin Address', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Address for the <strong>sender</strong>.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_origin_city' => [
                'title' => __('Origin City', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('City for the <strong>sender</strong>.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_origin_country' => [
                'type' => 'single_select_country',
            ],
            'gophr_origin_postcode' => [
                'title' => __('Origin Postcode', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Zip/postcode for the <strong>sender</strong>.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_phone_number' => [
                'title' => __('Your Phone Number', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Your contact phone number.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_email_address' => [
                'title' => __('Your Email Address', 'gophr-woocommerce'),
                'type' => 'text',
                'description' => __('Your email address.', 'gophr-woocommerce'),
                'default' => '',
            ],
            'gophr_services_packaging' => [
                'title' => __('Services', 'gophr-woocommerce'),
                'type' => 'title',
                'description' => '',
            ],
            'gophr_services' => [
                'type' => 'services'
            ],
            'gophr_ship_from_address' => [
                'title' => __('Ship To Address', 'gophr-woocommerce'),
                'type' => 'select',
                'default' => 'shipping_address',
                'options' => [
                    'shipping_address' => __('Shipping Address', 'gophr-woocommerce'),
                    'billing_address' => __('Billing Address', 'gophr-woocommerce'),
                ],
                'description' => __('Change the preferance of Shipping Address printed on the label.', 'gophr-woocommerce')
            ],
            'gophr_disable_shipment_tracking' => [
                'title' => __('Shipment Tracking', 'gophr-woocommerce'),
                'type' => 'select',
                'default' => 'yes',
                'options' => [
                    'TrueForCustomer' => __('Disable for Customer', 'gophr-woocommerce'),
                    'False' => __('Enable', 'gophr-woocommerce'),
                    'True' => __('Disable', 'gophr-woocommerce'),
                ],
                'description' => __('Selecting Disable for customer will hide shipment tracking info from customer side order details page.', 'gophr-woocommerce')
            ],
            'gophr_insurance_required' => [
                'title' => __('Insured Value', 'gophr-woocommerce'),
                'label' => __('Request Insurance to be included in Gophr rates', 'gophr-woocommerce'),
                'type' => 'checkbox',
                'default' => 'no',
                'description' => __('Enabling this will include insurance in Gophr rates', 'gophr-woocommerce')
            ],
        ];
    }
}