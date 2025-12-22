<?php

namespace Gophr\Woocommerce\Admin;

class FormFields
{
    public  static function getFields(): array
    {
        return [
            'enabled' => [
                'title' => __('Enable/Disable', 'gophr-woocommerce-shipping'),
                'type' => 'checkbox',
                'label' => __('Enable this shipping method', 'gophr-woocommerce-shipping'),
                'default' => 'no'
            ],
            'title' => [
                'title' => __('Shipping Title', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'gophr-woocommerce-shipping'),
                'default' => __('Gophr', 'gophr-woocommerce-shipping')
            ],
            'api' => [
                'title' => __('API Settings', 'gophr-woocommerce-shipping'),
                'type' => 'title',
            ],
            'access_key' => [
                'title' => __('Gophr API Key', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('Create account at book.gophr.com and request API key though live chat or email help@gophr.com.', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'api_mode' => [
                'title' => __('API Key Mode', 'gophr-woocommerce-shipping'),
                'type' => 'select',
                'default' => 'yes',
                'options' => [
                    'Live' => __('Live', 'gophr-woocommerce-shipping'),
                    'Test' => __('Test', 'gophr-woocommerce-shipping'),
                ],
                'description' => __('Set as Test to switch to Gophr api test servers. Transaction will be treated as sample transactions by Gophr.', 'gophr-woocommerce-shipping')
            ],
            'gophr_availability' => [
                'title' => __('Gophr Availability', 'gophr-woocommerce-shipping'),
                'type' => 'title',
                'description' => '',
            ],
            'gophr_working_hours' => [
                'type' => 'working_hours',
            ],
            'order_preparation_time' => [
                'title' => __('Order preparation time', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'css' => "width:2em",
                'description' => __('Order preparation and packaging time in hours.', 'gophr-woocommerce-shipping'),
                'default' => '1',
            ],
            'origin_info' => [
                'title' => __('Origin Information', 'gophr-woocommerce-shipping'),
                'type' => 'title',
            ],
            'gophr_user_name' => [
                'title' => __('Your Name', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('Enter your name', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'origin_company_name' => [
                'title' => __('Company Name', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('Your business/attention name.', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'origin_addressline' => [
                'title' => __('Origin Address', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('Address for the <strong>sender</strong>.', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'origin_city' => [
                'title' => __('Origin City', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('City for the <strong>sender</strong>.', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'origin_country_state' => [
                'type' => 'single_select_country',
            ],
            'origin_postcode' => [
                'title' => __('Origin Postcode', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('Zip/postcode for the <strong>sender</strong>.', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'phone_number' => [
                'title' => __('Your Phone Number', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('Your contact phone number.', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'email_address' => [
                'title' => __('Your Email Address', 'gophr-woocommerce-shipping'),
                'type' => 'text',
                'description' => __('Your email address.', 'gophr-woocommerce-shipping'),
                'default' => '',
            ],
            'services_packaging' => [
                'title' => __('Services', 'gophr-woocommerce-shipping'),
                'type' => 'title',
                'description' => '',
            ],
            'services' => [
                'type' => 'services'
            ],
            'ship_from_address' => [
                'title' => __('Ship To Address', 'gophr-woocommerce-shipping'),
                'type' => 'select',
                'default' => 'shipping_address',
                'options' => [
                    'shipping_address' => __('Shipping Address', 'gophr-woocommerce-shipping'),
                    'billing_address' => __('Billing Address', 'gophr-woocommerce-shipping'),
                ],
                'description' => __('Change the preferance of Shipping Address printed on the label.', 'gophr-woocommerce-shipping')
            ],
            'disble_shipment_tracking' => [
                'title' => __('Shipment Tracking', 'gophr-woocommerce-shipping'),
                'type' => 'select',
                'default' => 'yes',
                'options' => [
                    'TrueForCustomer' => __('Disable for Customer', 'gophr-woocommerce-shipping'),
                    'False' => __('Enable', 'gophr-woocommerce-shipping'),
                    'True' => __('Disable', 'gophr-woocommerce-shipping'),
                ],
                'description' => __('Selecting Disable for customer will hide shipment tracking info from customer side order details page.', 'gophr-woocommerce-shipping')
            ],
            'insurance_required' => [
                'title' => __('Insured Value', 'gophr-woocommerce-shipping'),
                'label' => __('Request Insurance to be included in Gophr rates', 'gophr-woocommerce-shipping'),
                'type' => 'checkbox',
                'default' => 'no',
                'description' => __('Enabling this will include insurance in Gophr rates', 'gophr-woocommerce-shipping')
            ],
        ];
    }
}