<?php

namespace Shimango\Gophr\Http\Responses\Deliveries;

use Shimango\Gophr\DataTransferObjects\Response\Deliveries\GetDeliveryResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class GetDeliveryResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?GetDeliveryResponseDto
    {
        return parent::getDataTransferObject(GetDeliveryResponseDto::class);
    }
}
