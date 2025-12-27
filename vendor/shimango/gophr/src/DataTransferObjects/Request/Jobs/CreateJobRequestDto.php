<?php

namespace Shimango\Gophr\DataTransferObjects\Request\Jobs;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class CreateJobRequestDto extends AbstractDataTransferObject
{
    public ?int $vehicle_type = null;

    public ?int $is_confirmed = null;

    public ?int $job_priority = null;

    public ?int $is_fixed_sequence = null;

    /** @var \Shimango\Gophr\DataTransferObjects\Pickups\PickupDto[] */
    public $pickups = [];

    /** @var \Shimango\Gophr\DataTransferObjects\Dropoffs\DropoffDto[] */
    public $dropoffs = [];

    public ?array $meta_data;
}
