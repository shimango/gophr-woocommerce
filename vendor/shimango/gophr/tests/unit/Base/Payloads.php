<?php

namespace Shimango\Gophr\Tests\Unit\Base;

use DateTimeInterface;

class Payloads
{
    public const MULTI_JOB_TYPE_MASTER_MULTI_DROP = 112;

    private static function getParcelExternalId(bool $increment = false): string
    {
        static $parcel_external_id = 1;
        if ($increment) {
            $parcel_external_id++;
        }

        return str_pad($parcel_external_id, 3, '0', STR_PAD_LEFT);
    }

    private static function getSequenceNumber(bool $increment = false): int
    {
        static $sequence_number = 1;
        if ($increment) {
            $sequence_number++;
        }
        return $sequence_number;
    }

    public static function getCreateJobPayload(array $customValues = []): array
    {
        $defaultValues = [
            "vehicle_type" => 40,
            "is_confirmed" => 0,
            "multi_job_type" => self::MULTI_JOB_TYPE_MASTER_MULTI_DROP,
            "pickups" => [
                self::getJobPickup(['sequence_number' => self::getSequenceNumber()]),
            ],
            "dropoffs" => [
                self::getJobDropoff(['sequence_number' => self::getSequenceNumber(true)]),
            ],
            "webhook_url" => "https://www.test.com",
            "meta_data" => [
                [
                    "key" => "123882383299439bfnbfb838934838348",
                    "value" => "433434mfdferklfkdmk344343443"
                ]
            ]
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getUpdateJobPayload(array $customValues = []): array
    {
        $defaultValues = [
            'is_confirmed' => 1
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getCancelJobPayload(array $customValues = []): array
    {
        $defaultValues = [
            'cancelled_reason' => 'TEST_ORDER',
            'cancelled_comment' => 'Order was placed by mistake',
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getCancelDeliveryPayload(array $customValues = []): array
    {
        $defaultValues = [
            'cancelled_reason' => 'TEST_ORDER',
            'cancelled_comment' => 'Order was placed by mistake',
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getCreateDeliveryPayload(array $customValues = []): array
    {
        $defaultValues = [
            "pickup" => self::getDeliveryPickup(),
            "dropoff" => self::getDeliveryDropoff([
                'sequence_number' => self::getSequenceNumber(true),
                "dropoff_address1" => "10 Downing Street",
                "dropoff_address2" => "Flat 10",
                "dropoff_city" => "London",
                "dropoff_postcode" => "SW1A 2AA",
                "dropoff_country_code" => "GB",

                "dropoff_location_lat" => "51.5" . rand(10000, 99999) . '',
                "dropoff_location_lng" => "-0.0" . rand(10000, 99999) . '',
                "dropoff_tips_how_to_find" => "Take junction 5 off the M1.",
                "dropoff_person_name" => "Jane Doe",
                "dropoff_email" => "bozo@test.com",
            ]),
            "parcels" => [
                self::getCreateParcelPayload()
            ]
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getUpdateDeliveryPayload(array $customValues = []): array
    {
        return [
            'pickup' => self::getDeliveryPickup($customValues['pickup'] ?? []),
            'dropoff' => self::getDeliveryDropoff($customValues['dropoff'] ?? ['sequence_number' => self::getSequenceNumber(true)]),
            'parcels' => [
                self::getUpdateParcelPayload($customValues['parcels'] ?? []),
            ]
        ];
    }

    public static function getCreateParcelPayload(array $customValues = []): array
    {
        $defaultValues = [
            "parcel_external_id" => self::getPArcelExternalId(true),
            "parcel_reference_number" => "7fc35278-60a1-4fb3-9791-4f45c492e120",
            "parcel_description" => "Big2!",
            "parcel_insurance_value" => 150,
            "id_check" => 0,
            "width" => 0.005,
            "length" => 0.005,
            "height" => 0.005,
            "weight" => 0.005,
            "is_food" => 0,
            "is_fragile" => 0,
            "is_liquid" => 0,
            "is_not_rotatable" => 0,
            "is_glass" => 0,
            "is_baked" => 0,
            "is_flower" => 0,
            "is_alcohol" => 0,
            "is_beef" => 0,
            "is_pork" => 0
        ];

        return array_merge($customValues, $defaultValues);
    }

    public static function getUpdateParcelPayload(array $customValues = []): array
    {
        $defaultValues = self::getCreateParcelPayload(['parcel_description' => 'Updated parcel']);
        return array_merge($defaultValues, $customValues);
    }

    public static function getJobPickup(array $customValues = []): array
    {
        $defaultValues = [
            "earliest_pickup_time" => (new \DateTime('tomorrow noon'))->format(DateTimeInterface::ATOM),
            "pickup_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT2H'))->format(DateTimeInterface::ATOM),
            "pickup_address1" => "1-4 Pope Street",
            "pickup_city" => "London",
            "pickup_postcode" => "SE1 3PR",
            "pickup_country_code" => "GB",
            "pickup_location_lat" => "51.4997819",
            "pickup_location_lng" => "-0.0784133",
            "pickup_company_name" => "Gophr Ltd",
            "pickup_person_name" => "John Smith",
            "pickup_email" => "john.smith@gophr.com",
            "pickup_mobile_number" => "07711111112",
            "pickup_phone_number" => "07722222232",
            "pickup_proof_required" => 1,
            "is_first_pickup" => 0,
            "sequence_number" => 1,
            "parcels" => [
                self::getCreateParcelPayload(),
            ]
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getJobDropoff(array $customValues = []): array
    {
        $defaultValues = [
            "min_required_age" => 0,
            "dropoff_company_name" => "Private Investigators",
            "dropoff_address1" => "221b Baker St",
            "dropoff_city" => "London",
            "dropoff_postcode" => "NW1 6XE",
            "dropoff_country_code" => "GB",
            "dropoff_location_lat" => "51.49" . rand(100000, 999999),
            "dropoff_location_lng" => "-0.17" . rand(100000, 999999),
            "dropoff_tips_how_to_find" => "It's elementary my dear Watson",
            "dropoff_person_name" => "Sherlock Holmes",
            "dropoff_email" => "sherlok.holmer@gohr.com",
            "dropoff_mobile_number" => "07766663666",
            "dropoff_phone_number" => "07735555555",
            "earliest_dropoff_time" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT2H'))->format(DateTimeInterface::ATOM),
            "dropoff_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT4H'))->format(DateTimeInterface::ATOM),
            "dropoff_proof_required" => 0,
            "cold_chain" => 0,
            "is_final_dropoff" => 0,
            "sequence_number" => 2,
            "leg_type" => "STANDARD",
            "parcels" => [
                [
                    "parcel_external_id" => self::getParcelExternalId(),
                ]
            ]
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getDeliveryPickup(array $customValues = []): array
    {
        $defaultValues = [
            "pickup_proof_required" => 1,
            "earliest_pickup_time" => (new \DateTime('tomorrow noon'))->format(DateTimeInterface::ATOM),
            "pickup_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT2H'))->format(DateTimeInterface::ATOM),
            "pickup_address1" => "1-4 Pope Street",
            "pickup_city" => "London",
            "pickup_postcode" => "SE1 3PR",
            "pickup_country_code" => "GB",
            "pickup_location_lat" => "51.4997819",
            "pickup_location_lng" => "-0.0784133",
            "pickup_company_name" => "Gophr Ltd",
            "pickup_person_name" => "John Smith",
            "pickup_email" => "john.smith@gophr.com",
            "pickup_mobile_number" => "07711111112",
            "pickup_phone_number" => "07722222232",
        ];

        return array_merge($defaultValues, $customValues);
    }

    public static function getDeliveryDropoff(array $customValues = []): array
    {
        $defaultValues = [
            "dropoff_proof_required" => 1,
            "min_required_age" => 0,

            "dropoff_company_name" => "The Firm",
            "dropoff_address1" => "Buckingham Palace",
            "dropoff_city" => "London",
            "dropoff_postcode" => "SW1A 1AA",
            "dropoff_country_code" => "GB",
            "dropoff_location_lat" => "51.501476",
            "dropoff_location_lng" => "-0.140634",
            "dropoff_tips_how_to_find" => "Follow the gards",
            "dropoff_person_name" => "Betty",
            "dropoff_email" => "elizabeth@gophr.com",
            "dropoff_mobile_number" => "07766666666",
            "dropoff_phone_number" => "07755555555",
            "dropoff_instructions" => "Please leave at the gate",
            "earliest_dropoff_time" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT4H'))->format(DateTimeInterface::ATOM),
            "dropoff_deadline" => (new \DateTime('tomorrow noon'))->add(new \DateInterval('PT5H'))->format(DateTimeInterface::ATOM),
            "cold_chain" => 60,
            "sequence_number" => 1
        ];

        return array_merge($defaultValues, $customValues);
    }
}
