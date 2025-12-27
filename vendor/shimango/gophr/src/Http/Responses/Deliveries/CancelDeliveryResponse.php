<?php

namespace Shimango\Gophr\Http\Responses\Deliveries;

use Shimango\Gophr\DataTransferObjects\Response\Deliveries\CancelDeliveryResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class CancelDeliveryResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?CancelDeliveryResponseDto
    {
        return parent::getDataTransferObject(CancelDeliveryResponseDto::class);
    }
}
