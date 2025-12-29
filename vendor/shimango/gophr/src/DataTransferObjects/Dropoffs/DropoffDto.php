<?php

namespace Shimango\Gophr\DataTransferObjects\Dropoffs;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class DropoffDto extends AbstractDataTransferObject
{
    public ?int $min_required_age = null;

    public ?string $dropoff_company_name = null;

    public ?string $dropoff_address1 = null;

    public ?string $dropoff_address2 = null;

    public ?string $dropoff_city = null;

    public ?string $dropoff_postcode = null;

    public ?string $dropoff_country_code = null;

    public ?string $dropoff_w3w = null;

    public ?string $dropoff_location_lat = null;

    public ?string $dropoff_location_lng = null;

    public ?string $dropoff_tips_how_to_find = null;

    public ?string $dropoff_person_name = null;

    public ?string $dropoff_email = null;

    public ?string $dropoff_mobile_number = null;

    public ?string $dropoff_phone_number = null;

    public ?string $dropoff_instructions = null;

    public ?string $earliest_dropoff_time = null;

    public ?string $dropoff_deadline = null;

    public ?int $dropoff_proof_required = null;

    public ?int $cold_chain = null;

    public ?int $sequence_number = null;

    public $parcels = [];
}
