<?php

namespace Shimango\Gophr\Http\Responses\Jobs;

use Shimango\Gophr\DataTransferObjects\Response\Jobs\GetQuoteResponseDto;
use Shimango\Gophr\Http\AbstractGophrResponse;

final class GetQuoteResponse extends AbstractGophrResponse
{
    public function getContentsObject(): ?GetQuoteResponseDto
    {
        return parent::getDataTransferObject(GetQuoteResponseDto::class);
    }
}
