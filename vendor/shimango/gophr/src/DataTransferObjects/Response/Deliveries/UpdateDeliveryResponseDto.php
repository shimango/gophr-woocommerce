<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Deliveries;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Response\Deliveries\UpdateDeliveryDto;

final class UpdateDeliveryResponseDto extends AbstractDataTransferObject
{
    public ?UpdateDeliveryDto $data = null;
}
