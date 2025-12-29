<?php

namespace Shimango\Gophr\DataTransferObjects;

use Shimango\Gophr\Interfaces\DataTransferObjectInterface;
use Spatie\DataTransferObject\DataTransferObject;

abstract class AbstractDataTransferObject extends DataTransferObject implements DataTransferObjectInterface
{
    /**
     * @var string[]
     */
    private const FLOAT_FIELDS = [
        'amount', 'distance'
    ];

    public function __construct(array $parameters = [])
    {
        foreach (self::FLOAT_FIELDS as $floatField) {
            if (isset($parameters[$floatField]) && is_int($parameters[$floatField])) {
                $parameters[$floatField] = (float)$parameters[$floatField];
            }
        }

        parent::__construct($parameters);
    }

    public function toArray(): array
    {
        return array_filter(parent::toArray());
    }
}
