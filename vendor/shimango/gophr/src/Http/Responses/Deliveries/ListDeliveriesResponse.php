<?php

namespace Shimango\Gophr\Http\Responses\Deliveries;

use Shimango\Gophr\DataTransferObjects\Response\Deliveries\ListDeliveriesResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class ListDeliveriesResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?ListDeliveriesResponseDto
    {
        return parent::getDataTransferObject(ListDeliveriesResponseDto::class);
    }
}
