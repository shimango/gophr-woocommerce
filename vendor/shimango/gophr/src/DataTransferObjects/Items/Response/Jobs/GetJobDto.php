<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Jobs;

final class GetJobDto extends CreateJobDto
{
    public ?string $job_number = null;
}
