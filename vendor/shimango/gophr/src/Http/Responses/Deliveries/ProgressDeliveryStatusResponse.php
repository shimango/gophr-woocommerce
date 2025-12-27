<?php

namespace Shimango\Gophr\Http\Responses\Deliveries;

use Shimango\Gophr\DataTransferObjects\Response\Deliveries\ProgressDeliveryStatusResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class ProgressDeliveryStatusResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?ProgressDeliveryStatusResponseDto
    {
        return parent::getDataTransferObject(ProgressDeliveryStatusResponseDto::class);
    }
}
