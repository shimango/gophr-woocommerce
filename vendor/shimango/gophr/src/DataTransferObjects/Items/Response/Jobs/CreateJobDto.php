<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Jobs;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\MoneyDto;

class CreateJobDto extends AbstractDataTransferObject
{
    public string $job_id;

    public ?int $is_confirmed = null;

    public ?int $vehicle_type = null;

    public MoneyDto $price_net;

    public MoneyDto $price_gross;

    public ?int $job_priority = null;

    public ?float $distance = null;

    /** @var \Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries\PartialDeliveryDto[] */
    public $deliveries = [];
}
