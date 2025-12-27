<?php

namespace Shimango\Gophr\Http\Responses\Parcels;

use Shimango\Gophr\DataTransferObjects\Response\Parcels\UpdateParcelResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class UpdateParcelResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?UpdateParcelResponseDto
    {
        return parent::getDataTransferObject(UpdateParcelResponseDto::class);
    }
}
