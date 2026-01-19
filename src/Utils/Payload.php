<?php

namespace Gophr\Woocommerce\Utils;

use Shimango\Gophr\DataTransferObjects\Dropoffs\DropoffDto;
use Shimango\Gophr\DataTransferObjects\Parcels\ParcelDto;
use Shimango\Gophr\DataTransferObjects\Pickups\PickupDto;
use Shimango\Gophr\DataTransferObjects\Request\Jobs\CreateJobRequestDto;
use Shimango\Gophr\DataTransferObjects\Request\Parcels\CreateParcelRequestDto;
use WC_Product_Simple;

class Payload
{
    /**
     * @param array $package
     * @return array<CreateParcelRequestDto>
     */
    public static function getParcelsPayload(array $package): array
    {
        $parcels = [];

        foreach ($package['contents'] as $itemId => $item) {
            /** @var WC_Product_Simple $product */
            $product = $item['data'];
            $quantity = (int) $item['quantity'];

            // Get parcel handling flags from settings
            $parcelFlags = get_option('gophr_parcel_flags', []);
            $idCheck = get_option('gophr_id_check', 'no') === 'yes' ? 1 : null;

            // Create a parcel for each item quantity
            for ($i = 0; $i < $quantity; $i++) {
                $parcelData = [
                    "parcel_external_id" => (string) $item['key'] . '_' . $i,
                    "parcel_reference_number" => (string) $product->get_id(),
                    "parcel_description" => $product->get_name(),
                    "width" => (float) $product->get_width(),
                    "length" => (float) $product->get_length(),
                    "height" => (float) $product->get_height(),
                    "weight" => (float) $product->get_weight(),
                ];

                // Add parcel flags if set
                if (!empty($parcelFlags['is_food']) && $parcelFlags['is_food'] === 'yes') {
                    $parcelData['is_food'] = 1;
                }
                if (!empty($parcelFlags['is_fragile']) && $parcelFlags['is_fragile'] === 'yes') {
                    $parcelData['is_fragile'] = 1;
                }
                if (!empty($parcelFlags['is_liquid']) && $parcelFlags['is_liquid'] === 'yes') {
                    $parcelData['is_liquid'] = 1;
                }
                if (!empty($parcelFlags['is_glass']) && $parcelFlags['is_glass'] === 'yes') {
                    $parcelData['is_glass'] = 1;
                }

                // Add ID check if enabled
                if ($idCheck) {
                    $parcelData['id_check'] = $idCheck;
                }

                $parcels[] = new ParcelDto($parcelData);
            }
        }

        return array_filter($parcels);
    }

    /**
     * @param array $order
     * @param array<CreateParcelRequestDto> $parcels
     * @return CreateJobRequestDto
     */
    public static function getRequestPayload(array $order, ?array $parcels = null): CreateJobRequestDto
    {
        $parcels ??= Payload::getParcelsPayload($order);

        $origin = [
            "pickup_company_name" => get_option('gophr_origin_company_name', ''),
            "pickup_person_name" => get_option('gophr_user_name', ''),
            "pickup_mobile_number" => get_option('gophr_phone_number', ''),
            "pickup_phone_number" => get_option('gophr_phone_number', ''),
            "pickup_address1" => get_option('gophr_origin_address1', get_option('woocommerce_store_address')),
            "pickup_address2" => get_option('gophr_origin_address2', get_option('woocommerce_store_address_2')),
            "pickup_city" => get_option('gophr_origin_city', get_option('woocommerce_store_city')),
            "pickup_postcode" => get_option('gophr_origin_postcode', get_option('woocommerce_store_postcode')),
            "pickup_country_code" => get_option('gophr_origin_country', substr(get_option('woocommerce_default_country'), 0, 2)),
        ];

        $destination = $order['destination'];

        $pickups = self::getGophrPickupPayload($origin, $parcels);
        $dropoffs = self::getGophrDropoffPayload($destination, $parcels);

        $jobData = [
            "external_id" => $order['external_id'],
            "is_confirmed" => 1,
            "pickups" => [$pickups],
            "dropoffs" => [$dropoffs],
            "meta_data" => [["booking_method" => GOPHR_PLUGIN_NAME]],
        ];

        // Add vehicle type if set
        $vehicleType = get_option('gophr_vehicle_type', '');
        if (!empty($vehicleType)) {
            $jobData['vehicle_type'] = (int) $vehicleType;
        }

        return new CreateJobRequestDto($jobData);
    }

    private static function getGophrPickupPayload(array $origin, array $parcels): PickupDto
    {
        $pickup = [
            "pickup_address1" => $origin['pickup_address1'],
            "pickup_address2" => $origin['pickup_address2'],
            "pickup_city" => $origin['pickup_city'],
            "pickup_postcode" => $origin['pickup_postcode'],
            "pickup_country_code" => $origin['pickup_country_code'],
            "pickup_company_name" => $origin['pickup_company_name'],
            "pickup_person_name" => $origin['pickup_person_name'],
            "pickup_mobile_number" => $origin['pickup_mobile_number'],
            "pickup_phone_number" => $origin['pickup_phone_number'],
            "parcels" => $parcels,
        ];

        // Add pickup proof required if set
        $pickupProof = get_option('gophr_pickup_proof_required', '');
        if ($pickupProof !== '') {
            $pickup['pickup_proof_required'] = (int) $pickupProof;
        }

        // Add pickup instructions if set
        $pickupInstructions = get_option('gophr_pickup_instructions', '');
        if (!empty($pickupInstructions)) {
            $pickup['pickup_instructions'] = $pickupInstructions;
        }

        // Add pickup tips if set
        $pickupTips = get_option('gophr_pickup_tips', '');
        if (!empty($pickupTips)) {
            $pickup['pickup_tips_how_to_find'] = $pickupTips;
        }

        // Add origin email if set
        $originEmail = get_option('gophr_email_address', '');
        if (!empty($originEmail)) {
            $pickup['pickup_email'] = $originEmail;
        }

        return new PickupDto($pickup);
    }

    private static function getGophrDropoffPayload(array $destination, array $parcels): DropoffDto
    {
        $dropoff = [
            "dropoff_company_name" => $destination['company'] ?? null,
            "dropoff_address1" => $destination['address_1'] ?? null,
            "dropoff_address2" => $destination['address_2'] ?? null,
            "dropoff_city" => $destination['city'] ?? null,
            "dropoff_postcode" => $destination['postcode'] ?? null,
            "dropoff_country_code" => $destination['country'],
            "dropoff_person_name" => trim(sprintf('%s %s', $destination['first_name'] ?? null, $destination['last_name'] ?? null)),
            "dropoff_mobile_number" => $destination['phone'] ?? null,
            "dropoff_phone_number" => $destination['phone'] ?? null,
            "sequence_number" => 2,
            "parcels" => array_map(fn($parcel) => ['parcel_external_id' => $parcel->parcel_external_id], $parcels),
        ];

        // Add dropoff proof required if set
        $dropoffProof = get_option('gophr_dropoff_proof_required', '');
        if ($dropoffProof !== '') {
            $dropoff['dropoff_proof_required'] = (int) $dropoffProof;
        }

        // Add age verification if set
        $minAge = get_option('gophr_min_required_age', '');
        if (!empty($minAge)) {
            $dropoff['min_required_age'] = (int) $minAge;
        }

        // Add PIN required if enabled
        $pinRequired = get_option('gophr_pin_required', 'no');
        if ($pinRequired === 'yes') {
            $dropoff['pin_required'] = 1;
        }

        // Add cold chain if set
        $coldChain = get_option('gophr_cold_chain', '');
        if (!empty($coldChain) && is_numeric($coldChain)) {
            $dropoff['cold_chain'] = (int) $coldChain;
        }

        // Add customer email if available
        if (!empty($destination['email'])) {
            $dropoff['dropoff_email'] = $destination['email'];
        }

        return new DropoffDto(array_filter($dropoff));
    }
}