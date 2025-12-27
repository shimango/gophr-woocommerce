<?php

namespace Shimango\Gophr\DataTransferObjects\Pickups;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class PickupDto extends AbstractDataTransferObject
{
    public ?string $earliest_pickup_time = null;

    public ?string $pickup_deadline = null;

    public ?string $pickup_address1 = null;

    public ?string $pickup_address2 = null;

    public ?string $pickup_city = null;

    public ?string $pickup_postcode = null;

    public ?string $pickup_country_code = null;

    public ?string $pickup_w3w = null;

    public ?string $pickup_location_lat = null;

    public ?string $pickup_location_lng = null;

    public ?string $pickup_company_name = null;

    public ?string $pickup_tips_how_to_find = null;

    public ?string $pickup_person_name = null;

    public ?string $pickup_email = null;

    public ?string $pickup_mobile_number = null;

    public ?string $pickup_phone_number = null;

    public ?string $pickup_instructions = null;

    public ?int $pickup_proof_required = null;

    public ?int $sequence_number = null;
}
