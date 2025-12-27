<?php

namespace Shimango\Gophr\Http\Responses\Parcels;

use Shimango\Gophr\DataTransferObjects\Response\Parcels\CreateParcelResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class CreateParcelResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?CreateParcelResponseDto
    {
        return parent::getDataTransferObject(CreateParcelResponseDto::class);
    }
}
