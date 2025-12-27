<?php

namespace Shimango\Gophr\Http\Responses\Deliveries;

use Shimango\Gophr\DataTransferObjects\Response\Deliveries\UpdateDeliveryResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class UpdateDeliveryResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?UpdateDeliveryResponseDto
    {
        return parent::getDataTransferObject(UpdateDeliveryResponseDto::class);
    }
}
