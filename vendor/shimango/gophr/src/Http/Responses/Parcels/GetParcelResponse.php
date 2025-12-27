<?php

namespace Shimango\Gophr\Http\Responses\Parcels;

use Shimango\Gophr\DataTransferObjects\Response\Parcels\GetParcelResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class GetParcelResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?GetParcelResponseDto
    {
        return parent::getDataTransferObject(GetParcelResponseDto::class);
    }
}
