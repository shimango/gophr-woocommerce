<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Parcels;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Response\Parcels\GetParcelDto;

final class GetParcelResponseDto extends AbstractDataTransferObject
{
    public ?GetParcelDto $data = null;
}
