<?php

namespace Shimango\Gophr\DataTransferObjects\Request\Jobs;

use Shimango\Gophr\DataTransferObjects\AbstractDataTransferObject;

final class CancelJobRequestDto extends AbstractDataTransferObject
{
    // ORDER_CANCELLED, WRONG_INFO, COURIER_LATE, DUPLICATED, FRAUDULENT_ORDER, NOT_ACCEPTED, NRT, RESCHEDULED,
    // TECHNICAL_ISSUES, TEST_ORDER, NO_NEED
    public ?string $cancelled_reason = null;

    public ?string $cancelled_comment = null;
}
