<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Jobs;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Links;
use Shimango\Gophr\DataTransferObjects\Items\Meta;
use Shimango\Gophr\DataTransferObjects\Items\Response\Jobs\ListJobsDto;

final class ListJobsResponseDto extends AbstractDataTransferObject
{
    public ?ListJobsDto $data = null;

    public ?Links $links = null;

    public ?Meta $meta = null;
}
