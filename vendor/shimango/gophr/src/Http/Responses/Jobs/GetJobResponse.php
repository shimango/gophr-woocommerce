<?php

namespace Shimango\Gophr\Http\Responses\Jobs;

use Shimango\Gophr\DataTransferObjects\Response\Jobs\GetJobResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class GetJobResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?GetJobResponseDto
    {
        return parent::getDataTransferObject(GetJobResponseDto::class);
    }
}
