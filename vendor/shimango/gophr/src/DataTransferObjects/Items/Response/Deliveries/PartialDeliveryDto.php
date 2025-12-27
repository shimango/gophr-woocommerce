<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class PartialDeliveryDto extends AbstractDataTransferObject
{
    public string $delivery_id;

    public ?string $status = null;

    public ?string $leg_type = null;

    public ?string $private_job_url = null;

    public ?string $public_tracker_url = null;

    public int $pickup_sequence_number;

    public int $dropoff_sequence_number;

    /** @var \Shimango\Gophr\DataTransferObjects\Items\ListParcelItem[] */
    public $parcels = [];
}
