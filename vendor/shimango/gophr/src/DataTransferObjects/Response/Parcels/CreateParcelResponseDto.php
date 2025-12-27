<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Parcels;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Response\Parcels\CreateParcelDto;

final class CreateParcelResponseDto extends AbstractDataTransferObject
{
    public ?CreateParcelDto $data = null;
}
