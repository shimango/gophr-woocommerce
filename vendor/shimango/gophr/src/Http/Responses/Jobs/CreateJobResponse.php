<?php

namespace Shimango\Gophr\Http\Responses\Jobs;

use Shimango\Gophr\DataTransferObjects\Response\Jobs\CreateJobResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class CreateJobResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?CreateJobResponseDto
    {
        return parent::getDataTransferObject(CreateJobResponseDto::class);
    }
}
