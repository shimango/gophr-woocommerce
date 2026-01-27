<?php

namespace Gophr\Woocommerce\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FormFields
{
    public  static function getFields(): array
    {
        return [
            'gophr_carrier_service_settings' => [
                'title' => __('Carrier service', 'gophr-same-day'),
                'type' => 'title',
            ],
            'gophr_enable' => [
                'title' => __('Enable/Disable', 'gophr-same-day'),
                'type' => 'checkbox',
                'label' => __('Enable this shipping method', 'gophr-same-day'),
                'default' => 'yes'
            ],
            'gophr_shipping_title' => [
                'title' => __('Shipping Title', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'gophr-same-day'),
                'default' => __('Gophr Same-Day', 'gophr-same-day')
            ],
            'gophr_api_settings' => [
                'title' => __('API Settings', 'gophr-same-day'),
                'type' => 'title',
            ],
            'gophr_api_key' => [
                'title' => __('Gophr API Key', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Create account at book.gophr.com and request API key though live chat or email help@gophr.com.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_environment' => [
                'title' => __('API Key Mode', 'gophr-same-day'),
                'type' => 'select',
                'default' => 'production',
                'options' => [
                    'production' => __('Production', 'gophr-same-day'),
                    'sandbox' => __('Sandbox', 'gophr-same-day'),
                ],
                'description' => __('Set as Test to switch to Gophr api test servers. Transaction will be treated as sample transactions by Gophr.', 'gophr-same-day')
            ],
            'gophr_availability' => [
                'title' => __('Availability', 'gophr-same-day'),
                'type' => 'title',
                'description' => '',
            ],
            'gophr_working_hours' => [
                'type' => 'working_hours',
            ],
            'gophr_preparation_time' => [
                'title' => __('Order preparation time', 'gophr-same-day'),
                'type' => 'text',
                'css' => "width:2em",
                'description' => __('Order preparation and packaging time in hours.', 'gophr-same-day'),
                'default' => '1',
            ],
            'gophr_origin_info' => [
                'title' => __('Origin Information', 'gophr-same-day'),
                'type' => 'title',
            ],
            'gophr_user_name' => [
                'title' => __('Your Name', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Enter your name', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_origin_company_name' => [
                'title' => __('Company Name', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Your business/attention name.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_origin_address1' => [
                'title' => __('Origin Address', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Address for the <strong>sender</strong>.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_origin_address2' => [
                'title' => __('Origin Address', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Address for the <strong>sender</strong>.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_origin_city' => [
                'title' => __('Origin City', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('City for the <strong>sender</strong>.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_origin_country' => [
                'type' => 'single_select_country',
            ],
            'gophr_origin_postcode' => [
                'title' => __('Origin Postcode', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Zip/postcode for the <strong>sender</strong>.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_phone_number' => [
                'title' => __('Your Phone Number', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Your contact phone number.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_email_address' => [
                'title' => __('Your Email Address', 'gophr-same-day'),
                'type' => 'text',
                'description' => __('Your email address.', 'gophr-same-day'),
                'default' => '',
            ],
            'gophr_services_packaging' => [
                'title' => __('Services', 'gophr-same-day'),
                'type' => 'title',
                'description' => '',
            ],
            'gophr_services' => [
                'type' => 'services'
            ],
            'gophr_ship_from_address' => [
                'title' => __('Ship To Address', 'gophr-same-day'),
                'type' => 'select',
                'default' => 'shipping_address',
                'options' => [
                    'shipping_address' => __('Shipping Address', 'gophr-same-day'),
                    'billing_address' => __('Billing Address', 'gophr-same-day'),
                ],
                'description' => __('Change the preferance of Shipping Address printed on the label.', 'gophr-same-day')
            ],
            'gophr_disable_shipment_tracking' => [
                'title' => __('Shipment Tracking', 'gophr-same-day'),
                'type' => 'select',
                'default' => 'yes',
                'options' => [
                    'TrueForCustomer' => __('Disable for Customer', 'gophr-same-day'),
                    'False' => __('Enable', 'gophr-same-day'),
                    'True' => __('Disable', 'gophr-same-day'),
                ],
                'description' => __('Selecting Disable for customer will hide shipment tracking info from customer side order details page.', 'gophr-same-day')
            ],
            'gophr_insurance_required' => [
                'title' => __('Insured Value', 'gophr-same-day'),
                'label' => __('Request Insurance to be included in Gophr rates', 'gophr-same-day'),
                'type' => 'checkbox',
                'default' => 'no',
                'description' => __('Enabling this will include insurance in Gophr rates', 'gophr-same-day')
            ],
            // Delivery Options Section
            'gophr_delivery_options' => [
                'title' => __('Delivery Options', 'gophr-same-day'),
                'type' => 'title',
                'description' => __('<strong>Note:</strong> When configured, these settings apply to ALL deliveries.', 'gophr-same-day'),
            ],
            'gophr_vehicle_type' => [
                'title' => __('Vehicle Type', 'gophr-same-day'),
                'type' => 'select',
                'default' => '',
                'options' => [
                    '' => __('Auto (recommended)', 'gophr-same-day'),
                    '10' => __('Bike', 'gophr-same-day'),
                    '15' => __('Cargo Bike', 'gophr-same-day'),
                    '20' => __('Motorbike', 'gophr-same-day'),
                    '30' => __('Car', 'gophr-same-day'),
                    '40' => __('Small Van', 'gophr-same-day'),
                    '50' => __('Large Van', 'gophr-same-day'),
                ],
                'description' => __('Select preferred vehicle type. Leave as Auto for automatic selection based on parcel size. When set, applies to all deliveries.', 'gophr-same-day')
            ],
            'gophr_pickup_proof_required' => [
                'title' => __('Proof of Pickup', 'gophr-same-day'),
                'type' => 'select',
                'default' => '',
                'options' => [
                    '' => __('None', 'gophr-same-day'),
                    '1' => __('Photo', 'gophr-same-day'),
                    '2' => __('Signature', 'gophr-same-day'),
                    '3' => __('Photo & Signature', 'gophr-same-day'),
                ],
                'description' => __('Require courier to provide proof when collecting parcels. When set, applies to all pickups.', 'gophr-same-day')
            ],
            'gophr_dropoff_proof_required' => [
                'title' => __('Proof of Delivery', 'gophr-same-day'),
                'type' => 'select',
                'default' => '',
                'options' => [
                    '' => __('None', 'gophr-same-day'),
                    '1' => __('Photo', 'gophr-same-day'),
                    '2' => __('Signature', 'gophr-same-day'),
                    '3' => __('Photo & Signature', 'gophr-same-day'),
                ],
                'description' => __('Require courier to provide proof on delivery. When set, applies to all deliveries.', 'gophr-same-day')
            ],
            'gophr_min_required_age' => [
                'title' => __('Age Verification', 'gophr-same-day'),
                'type' => 'select',
                'default' => '',
                'options' => [
                    '' => __('Disabled', 'gophr-same-day'),
                    '18' => __('18+', 'gophr-same-day'),
                    '21' => __('21+', 'gophr-same-day'),
                    '25' => __('25+', 'gophr-same-day'),
                ],
                'description' => __('Require age verification on delivery. Essential for alcohol or restricted items. When set, applies to all deliveries.', 'gophr-same-day')
            ],
            'gophr_pin_required' => [
                'title' => __('PIN Required', 'gophr-same-day'),
                'type' => 'checkbox',
                'label' => __('Require PIN code for delivery', 'gophr-same-day'),
                'default' => 'no',
                'description' => __('Require recipient to enter a PIN code to receive delivery. When enabled, applies to all deliveries.', 'gophr-same-day')
            ],
            'gophr_id_check' => [
                'title' => __('ID Check Required', 'gophr-same-day'),
                'type' => 'checkbox',
                'label' => __('Require ID verification on delivery', 'gophr-same-day'),
                'default' => 'no',
                'description' => __('Require identity verification on delivery. When enabled, applies to all deliveries.', 'gophr-same-day')
            ],
            // Pickup Instructions Section
            'gophr_pickup_instructions_section' => [
                'title' => __('Pickup Instructions', 'gophr-same-day'),
                'type' => 'title',
                'description' => __('<strong>Note:</strong> When configured, these instructions apply to ALL pickups.', 'gophr-same-day'),
            ],
            'gophr_pickup_instructions' => [
                'title' => __('Default Pickup Instructions', 'gophr-same-day'),
                'type' => 'textarea',
                'default' => '',
                'description' => __('Instructions for couriers when collecting parcels (e.g., "Ring bell, use side entrance"). When set, applies to all pickups.', 'gophr-same-day')
            ],
            'gophr_pickup_tips' => [
                'title' => __('Pickup Location Tips', 'gophr-same-day'),
                'type' => 'textarea',
                'default' => '',
                'description' => __('Help couriers find your location (e.g., "Red door next to the pharmacy"). When set, applies to all pickups.', 'gophr-same-day')
            ],
            // Parcel Handling Section
            'gophr_parcel_handling' => [
                'title' => __('Parcel Handling', 'gophr-same-day'),
                'type' => 'title',
                'description' => __('<strong>Note:</strong> When configured, these settings apply to ALL parcels in every delivery.', 'gophr-same-day'),
            ],
            'gophr_parcel_flags' => [
                'title' => __('Default Parcel Flags', 'gophr-same-day'),
                'type' => 'parcel_flags',
                'default' => [],
                'description' => __('Default handling requirements for all parcels. When set, applies to all parcels in every delivery.', 'gophr-same-day')
            ],
            'gophr_cold_chain' => [
                'title' => __('Cold Chain (seconds)', 'gophr-same-day'),
                'type' => 'text',
                'css' => 'width:6em',
                'default' => '',
                'description' => __('Maximum seconds parcels can be outside cold storage. For perishable goods only. When set, applies to all deliveries.', 'gophr-same-day')
            ],
        ];
    }
}