<?php

namespace Shimango\Gophr\DataTransferObjects\Request\Deliveries;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Dropoffs\DropoffDto;
use Shimango\Gophr\DataTransferObjects\Pickups\PickupDto;

final class CreateDeliveryRequestDto extends AbstractDataTransferObject
{
    public PickupDto $pickup;

    public DropoffDto $dropoff;

    /** @var \Shimango\Gophr\DataTransferObjects\Request\Parcels\CreateParcelRequestDto[] */
    public $parcels = [];
}
