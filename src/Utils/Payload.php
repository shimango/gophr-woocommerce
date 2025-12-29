<?php

namespace Gophr\Woocommerce\Utils;

use Constants;
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
            $quantity = (float) $item['quantity'];

            $length = $quantity * (float) $product->get_length();
            $width = $quantity * (float) $product->get_width();
            $height = $quantity * (float) $product->get_height();
            $weight = $quantity * (float) $product->get_weight();

            $parcels[] = new ParcelDto([
                "parcel_external_id" => (string) $item['key'],
                "parcel_reference_number" => (string) $product->get_id(),
                "parcel_description" => $product->get_name(),
//                "parcel_insurance_value" => 150,
//                "id_check" => 0,
                "width" => $width,
                "length" => $length,
                "height" => $height,
                "weight" => $weight,
//                "is_food" => 0,
//                "is_fragile" => 0,
//                "is_liquid" => 0,
//                "is_not_rotatable" => 0,
//                "is_glass" => 0,
//                "is_baked" => 0,
//                "is_flower" => 0,
//                "is_alcohol" => 0,
//                "is_beef" => 0,
//                "is_pork" => 0
            ]);
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
            "pickup_company_name" => 'Test',
            "pickup_person_name" => 'Test',
            "pickup_mobile_number" => '07588112233',
            "pickup_phone_number" => '01273115599',
            "pickup_address1" => get_option('woocommerce_store_address'),
            "pickup_address2" => get_option('woocommerce_store_address_2'),
            "pickup_city" => get_option('woocommerce_store_city'),
            "pickup_postcode" => get_option('woocommerce_store_postcode'),
            "pickup_country_code" => substr( get_option( 'woocommerce_default_country' ), 0, 2 ), // GB
        ];

        $destination = $order['destination'];

        $pickups = self::getGophrPickupPayload($origin, $parcels);
        $dropoffs = self::getGophrDropoffPayload($destination, $parcels);

        return new CreateJobRequestDto([
            "external_id" => $order['external_id'],
            "is_confirmed" => 1,
            "pickups" => [$pickups],
            "dropoffs" => [$dropoffs],
            "meta_data" => [["booking_method" => Constants::$GOPHR_SAME_DAY_PLUGIN]],
        ]);
    }

    private static function getGophrPickupPayload(array $origin, array $parcels): PickupDto
    {
        $pickup = [
//            "earliest_pickup_time" => (new \DateTime('tomorrow noon'))->format(DateTimeInterface::ATOM),
//            "pickup_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT2H'))->format(DateTimeInterface::ATOM),
            "pickup_address1" => $origin['pickup_address1'],
            "pickup_address2" => $origin['pickup_address2'],
            "pickup_city" => $origin['pickup_city'],
            "pickup_postcode" => $origin['pickup_postcode'],
            "pickup_country_code" => $origin['pickup_country_code'],
//            "pickup_location_lat" => "51.4997819",
//            "pickup_location_lng" => "-0.0784133",
            "pickup_company_name" => $origin['pickup_company_name'],
            "pickup_person_name" => $origin['pickup_person_name'],
//            "pickup_email" => "john.smith@gophr.com",
            "pickup_mobile_number" => $origin['pickup_mobile_number'],
            "pickup_phone_number" => $origin['pickup_phone_number'],
//            "pickup_proof_required" => 1,
//            "is_first_pickup" => 0,
//            "sequence_number" => 1,
            "parcels" => $parcels,
        ];

        return new PickupDto($pickup);
    }

    private static function getGophrDropoffPayload(array $destination, array $parcels): DropoffDto
    {
        $dropoff = [
//            "min_required_age" => 0,
            "dropoff_company_name" => $destination['company'] ?? null,
            "dropoff_address1" => $destination['address_1'] ?? null,
            "dropoff_address2" => $destination['address_2'] ?? null,
            "dropoff_city" => $destination['city'] ?? null,
            "dropoff_postcode" => $destination['postcode'] ?? null,
            "dropoff_country_code" => $destination['country'],
//            "dropoff_location_lat" => "51.49" . rand(100000, 999999),
//            "dropoff_location_lng" => "-0.17" . rand(100000, 999999),
//            "dropoff_tips_how_to_find" => "It's elementary my dear Watson",
            "dropoff_person_name" => trim(sprintf('%s %s', $destination['first_name'] ?? null, $destination['last_name'] ?? null)),
//            "dropoff_email" => "sherlok.holmer@gohr.com",
            "dropoff_mobile_number" => $destination['phone'] ?? null,
            "dropoff_phone_number" => $destination['phone'] ?? null,
//            "earliest_dropoff_time" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT2H'))->format(DateTimeInterface::ATOM),
//            "dropoff_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT4H'))->format(DateTimeInterface::ATOM),
//            "dropoff_proof_required" => 0,
//            "cold_chain" => 0,
//            "is_final_dropoff" => 0,
            "sequence_number" => 2,
//            "leg_type" => "STANDARD",
            "parcels" => array_map(fn($parcel) => ['parcel_external_id' => $parcel->parcel_external_id], $parcels),
        ];

        return new DropoffDto(array_filter($dropoff));
    }
}