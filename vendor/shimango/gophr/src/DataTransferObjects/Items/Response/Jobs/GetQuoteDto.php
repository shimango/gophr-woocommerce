<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Jobs;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\MoneyDto;

final class GetQuoteDto extends AbstractDataTransferObject
{
    public ?int $job_priority = null;

    public ?int $vehicle_type = null;

    public MoneyDto $price_net;

    public MoneyDto $price_gross;

    public ?string $pickup_eta = null;

    public ?string $delivery_eta = null;

    public ?int $min_realistic_time = null;
}
