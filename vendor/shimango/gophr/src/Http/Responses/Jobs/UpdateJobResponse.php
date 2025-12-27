<?php

namespace Shimango\Gophr\Http\Responses\Jobs;

use Shimango\Gophr\DataTransferObjects\Response\Jobs\UpdateJobResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class UpdateJobResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?UpdateJobResponseDto
    {
        return parent::getDataTransferObject(UpdateJobResponseDto::class);
    }
}
