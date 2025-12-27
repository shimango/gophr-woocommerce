<?php

namespace Shimango\Gophr\Interfaces;

interface DataTransferObjectInterface
{
    public function toArray(): array;

    public function all(): array;

    public static function arrayOf(array $arrayOfParameters): array;
}
