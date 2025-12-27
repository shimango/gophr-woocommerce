<?php

namespace Shimango\Gophr\DataTransferObjects\Items;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class Page extends AbstractDataTransferObject
{
    public int $current_page;

    public int $count;

    public int $total;
}
