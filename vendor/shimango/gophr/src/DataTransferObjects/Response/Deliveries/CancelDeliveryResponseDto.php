<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Deliveries;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries\CancelDeliveryDto;

final class CancelDeliveryResponseDto extends AbstractDataTransferObject
{
    public ?CancelDeliveryDto $data = null;
}
