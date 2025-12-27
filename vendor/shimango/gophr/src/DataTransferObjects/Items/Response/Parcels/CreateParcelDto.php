<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Parcels;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\MoneyDto;

class CreateParcelDto extends AbstractDataTransferObject
{
    public string $parcel_id;

    public string $delivery_id;

    public ?string $parcel_external_id = null;

    public ?string $parcel_reference_number = null;

    public ?string $parcel_description = null;

    public MoneyDto $parcel_insurance_value;

    public ?string $barcode_reference = null;

    public ?float $width = null;

    public ?float $length = null;

    public ?float $height = null;

    public ?float $weight = null;

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
