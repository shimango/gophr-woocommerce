<?php

namespace Shimango\Gophr\DataTransferObjects\Parcels;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

class ParcelDto extends AbstractDataTransferObject
{
    public string $parcel_external_id;

    public ?string $parcel_reference_number = null;

    public ?string $parcel_description = null;

    public ?float $parcel_insurance_value = null;

    public float $width;

    public float $length;

    public float $height;

    public float $weight;

    public ?int $id_check = null;

    public ?int $is_food = null;

    public ?int $is_fragile = null;

    public ?int $is_liquid = null;

    public ?int $is_not_rotatable = null;

    public ?int $is_glass = null;

    public ?int $is_baked = null;

    public ?int $is_flower = null;

    public ?int $is_alcohol = null;

    public ?int $is_beef = null;

    public ?int $is_pork = null;
}
