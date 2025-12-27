<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class ListDeliveriesDto extends AbstractDataTransferObject
{
    /** @var \Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries\GetDeliveryDto[] */
    public $deliveries = [];
}
