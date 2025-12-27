<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Dropoffs\DropoffDto;
use Shimango\Gophr\DataTransferObjects\Pickups\PickupDto;

class CreateDeliveryDto extends AbstractDataTransferObject
{
    public string $delivery_id;

    public ?string $status = null;

    public ?string $leg_type = null;

    public ?string $private_job_url = null;

    public ?string $public_tracker_url = null;

    public PickupDto $pickup;

    public DropoffDto $dropoff;

    /** @var \Shimango\Gophr\DataTransferObjects\Items\ListParcelItem[] */
    public $parcels = [];
}
