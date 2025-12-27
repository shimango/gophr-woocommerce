<?php

namespace Shimango\Gophr\DataTransferObjects\Items\Response\Parcels;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class DeleteParcelDto extends AbstractDataTransferObject
{
    public ?string $message = null;
}
