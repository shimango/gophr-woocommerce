<?php

namespace Shimango\Gophr\DataTransferObjects\Response\Parcels;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;
use Shimango\Gophr\DataTransferObjects\Items\Response\Parcels\UpdateParcelDto;

final class UpdateParcelResponseDto extends AbstractDataTransferObject
{
    public ?UpdateParcelDto $data = null;
}
