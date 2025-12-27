<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Jobs;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Response\Jobs\CancelJobDto;

final class CancelJobResponseDto extends AbstractDataTransferObject
{
    public ?CancelJobDto $data = null;
}
