<?php

namespace Shimango\Gophr\DataTransferObjects\Items;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class ListParcelItem extends AbstractDataTransferObject
{
    public string $parcel_id;

    public ?string $parcel_external_id = null;

    public ?string $barcode_reference = null;
}
