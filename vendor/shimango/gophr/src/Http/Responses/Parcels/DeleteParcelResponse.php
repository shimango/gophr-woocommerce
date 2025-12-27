<?php

namespace Shimango\Gophr\Http\Responses\Parcels;

use Shimango\Gophr\DataTransferObjects\Response\Parcels\DeleteParcelResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class DeleteParcelResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?DeleteParcelResponseDto
    {
        return parent::getDataTransferObject(DeleteParcelResponseDto::class);
    }
}
