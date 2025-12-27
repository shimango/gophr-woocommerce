<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Deliveries;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries\GetDeliveryDto;

final class GetDeliveryResponseDto extends AbstractDataTransferObject
{
    public ?GetDeliveryDto $data = null;
}
